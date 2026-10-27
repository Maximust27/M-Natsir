/**
 * Production-like SEO audit of real Laravel HTTP responses (Node 22, zero deps).
 * Usage: node scripts/audit-seo.mjs http://127.0.0.1:8000
 * Run against a disposable server with SEO_ALLOW_INDEXING=true.
 */
import assert from 'node:assert/strict';
import { mkdir, writeFile } from 'node:fs/promises';
import { resolve } from 'node:path';

const origin = new URL(process.argv[2] || 'http://127.0.0.1:8000').origin;
const outputDir = resolve('reports');
const failures = [];
const pages = [];
const links = new Set();
const images = new Set();
let checkedLinks = 0;
let checkedImages = 0;

const REQUIRED_PAGES = [
    '/', '/tentang', '/articles', '/library', '/media', '/contact',
    '/topik/anti-pencucian-uang',
    '/topik/pencucian-uang-profesional',
    '/topik/intelijen-keuangan',
];

async function check(context, fn) {
    try {
        await fn();
        console.log('PASS ' + context);
    } catch (error) {
        const message = error instanceof Error ? error.message : String(error);
        failures.push({ context, message });
        console.error('FAIL ' + context + ': ' + message);
    }
}

async function request(path, options = {}) {
    const response = await fetch(new URL(path, origin), {
        signal: AbortSignal.timeout(15000),
        ...options,
    });
    assert.equal(response.status, 200, path + ' returned HTTP ' + response.status);
    return response;
}

function tags(html, element) {
    return [...html.matchAll(new RegExp('<' + element + '\\b[^>]*>', 'gi'))].map((match) => match[0]);
}

function attribute(tag, name) {
    const found = new RegExp('\\b' + name + '\\s*=\\s*(["\\x27])([\\s\\S]*?)\\1', 'i').exec(tag);
    return found?.[2] ?? null;
}

function meta(html, key, value) {
    const found = tags(html, 'meta').filter((tag) => attribute(tag, key) === value);
    assert.equal(found.length, 1, 'expected exactly one ' + key + '=' + value);
    const content = attribute(found[0], 'content');
    assert.ok(content?.trim(), 'empty meta ' + value);
    return content;
}

function canonical(html) {
    const tagsFound = tags(html, 'link').filter((tag) => attribute(tag, 'rel') === 'canonical');
    assert.equal(tagsFound.length, 1, 'expected one canonical URL');
    return attribute(tagsFound[0], 'href');
}

function localPath(url) {
    const parsed = new URL(url, origin);
    assert.equal(parsed.origin, origin, 'unexpected URL origin: ' + url);
    return parsed.pathname + parsed.search;
}

function schema(html) {
    const matches = [...html.matchAll(/<script\s+type=["']application\/ld\+json["'][^>]*>([\s\S]*?)<\/script>/gi)];
    assert.equal(matches.length, 1, 'expected one JSON-LD block');
    const data = JSON.parse(matches[0][1]);
    assert.equal(data['@context'], 'https://schema.org');
    assert.ok(Array.isArray(data['@graph']));
    return data['@graph'];
}

async function audit() {
    await mkdir(outputDir, { recursive: true });
    const sitemapResponse = await request('/sitemap.xml');
    const sitemapText = await sitemapResponse.text();
    let sitemapURLs = [];

    await check('sitemap XML and canonical inventory', () => {
        assert.match(sitemapResponse.headers.get('content-type') || '', /application\/xml/);
        assert.match(sitemapText, /^<\?xml version="1\.0" encoding="UTF-8"\?>/);
        assert.match(sitemapText, /<urlset\b/);
        assert.match(sitemapText, /<\/urlset>\s*$/);
        sitemapURLs = [...sitemapText.matchAll(/<loc>([^<]+)<\/loc>/g)].map((x) => x[1]);
        assert.ok(sitemapURLs.length >= 10, 'too few sitemap URLs');
        assert.equal(new Set(sitemapURLs).size, sitemapURLs.length, 'duplicate URLs in sitemap');
        for (const url of sitemapURLs) {
            assert.equal(new URL(url).origin, origin, 'sitemap has wrong host');
            assert.doesNotMatch(url, /[?&](q|category|utm_source)=/, 'filtered URL in sitemap');
        }
        for (const path of REQUIRED_PAGES) {
            assert.ok(sitemapURLs.some((url) => localPath(url) === path), 'missing sitemap page: ' + path);
        }
    });

    await check('robots.txt reflects indexable audit mode', async () => {
        const response = await request('/robots.txt');
        assert.match(response.headers.get('content-type') || '', /text\/plain/);
        const robots = await response.text();
        assert.match(robots, /^User-agent: \*/m);
        assert.match(robots, /^Allow: \/$/m);
        assert.doesNotMatch(robots, /^Disallow: \/$/m);
        assert.ok(robots.includes('Sitemap: ' + origin + '/sitemap.xml'), 'wrong sitemap URL in robots');
    });

    const titles = new Map();
    const descriptions = new Map();

    for (const url of sitemapURLs) {
        const path = localPath(url);
        await check('indexable page ' + path, async () => {
            const response = await request(path);
            assert.match(response.headers.get('content-type') || '', /text\/html/);
            const html = await response.text();
            assert.match(html, /<html\b[^>]*\blang="id"/i);

            const headingCount = [...html.matchAll(/<h1\b[^>]*>/gi)].length;
            assert.equal(headingCount, 1, 'expected exactly one h1');

            const title = /<title>([\s\S]*?)<\/title>/i.exec(html)?.[1]?.trim();
            assert.ok(title?.length >= 15, 'missing or short HTML title');
            const description = meta(html, 'name', 'description');
            assert.ok(description.length >= 50 && description.length <= 200, 'description outside 50–200 characters');
            assert.equal(meta(html, 'name', 'robots'), 'index, follow');
            assert.equal(localPath(canonical(html)), path, 'canonical does not match URL');
            assert.equal(localPath(meta(html, 'property', 'og:url')), path, 'OG URL mismatch');
            assert.equal(meta(html, 'property', 'og:title'), title, 'OG title mismatch');
            assert.equal(meta(html, 'name', 'twitter:title'), title, 'Twitter title mismatch');
            assert.ok(meta(html, 'property', 'og:description').length >= 50);
            assert.ok(meta(html, 'property', 'og:image').startsWith(origin), 'OG image not absolute');

            const nodes = schema(html);
            const page = nodes.find((node) => ['WebPage', 'ProfilePage'].includes(node['@type']));
            assert.ok(page, 'missing WebPage/ProfilePage schema');
            assert.equal(localPath(page.url), path, 'schema URL mismatch');
            assert.equal(page.name, title);
            if (path === '/') {
                assert.ok(nodes.some((node) => node['@type'] === 'WebSite'));
                assert.ok(nodes.some((node) => node['@type'] === 'Person'));
            }
            if (path === '/tentang') {
                assert.equal(page['@type'], 'ProfilePage');
                assert.equal(page.mainEntity?.['@type'], 'Person');
            }

            // Paginated archive pages can legitimately reuse the archive title.
            if (!path.includes('?page=')) {
                assert.ok(!titles.has(title), 'duplicate title with ' + titles.get(title));
                assert.ok(!descriptions.has(description), 'duplicate description with ' + descriptions.get(description));
                titles.set(title, path);
                descriptions.set(description, path);
            }

            for (const img of tags(html, 'img')) {
                assert.notEqual(attribute(img, 'alt'), null, 'img missing alt attribute');
                const src = attribute(img, 'src');
                if (src) {
                    const asset = new URL(src, origin);
                    if (asset.origin === origin) images.add(asset.pathname);
                }
            }
            for (const source of tags(html, 'source')) {
                const srcset = attribute(source, 'srcset') || '';
                for (const entry of srcset.split(',')) {
                    const candidate = entry.trim().split(/\s+/)[0];
                    if (!candidate) continue;
                    const asset = new URL(candidate, origin);
                    if (asset.origin === origin) images.add(asset.pathname);
                }
            }
            for (const anchor of tags(html, 'a')) {
                const href = attribute(anchor, 'href');
                if (!href || href.startsWith('#') || /^(mailto:|tel:|javascript:)/i.test(href)) continue;
                const link = new URL(href, origin);
                if (link.origin === origin && link.pathname !== '/contact/cv') {
                    links.add(link.pathname + link.search);
                }
            }
            pages.push({ path, title, description, schemaType: page['@type'] });
        });
    }

    for (const [path, clean] of [
        ['/articles?q=uang', '/articles'],
        ['/articles?category=pml', '/articles'],
        ['/library?topic=asset-recovery', '/library'],
        ['/media?category=interviews', '/media'],
    ]) {
        await check('filtered page noindex ' + path, async () => {
            const html = await (await request(path)).text();
            assert.equal(meta(html, 'name', 'robots'), 'noindex, follow');
            assert.equal(localPath(canonical(html)), clean);
        });
    }

    await check('unknown topic returns HTTP 404', async () => {
        const response = await fetch(new URL('/topik/topik-tidak-ada', origin), {
            signal: AbortSignal.timeout(15000),
        });
        assert.equal(response.status, 404);
    });

    for (const link of links) {
        await check('internal link ' + link, async () => {
            await request(link);
            checkedLinks++;
        });
    }
    for (const img of images) {
        await check('local image ' + img, async () => {
            const response = await request(img, { method: 'HEAD' });
            assert.match(response.headers.get('content-type') || '', /^image\//, 'wrong image MIME');
            checkedImages++;
        });
    }

    return { sitemapCount: sitemapURLs.length };
}

let inventory = {};
try {
    inventory = await audit();
} catch (error) {
    const message = error instanceof Error ? error.message : String(error);
    failures.push({ context: 'audit setup', message });
    console.error('FAIL audit setup: ' + message);
}

const report = {
    checkedAt: new Date().toISOString(),
    baseUrl: origin,
    ...inventory,
    auditedPages: pages,
    checkedLinks,
    checkedImages,
    failures,
};
await mkdir(outputDir, { recursive: true });
await writeFile(resolve(outputDir, 'seo-audit.json'), JSON.stringify(report, null, 2) + '\n');
const summary = [
    '## HTTP SEO audit',
    '',
    '- Sitemap URLs: ' + (report.sitemapCount ?? 'n/a'),
    '- HTML pages checked: ' + pages.length,
    '- Internal links checked: ' + checkedLinks,
    '- Local images checked: ' + checkedImages,
    '- Failures: **' + failures.length + '**',
    '',
    ...(failures.length ? failures.map((item) => '- FAILED ' + item.context + ': ' + item.message) : ['All SEO smoke checks passed.']),
    '',
];
await writeFile(resolve(outputDir, 'seo-audit.md'), summary.join('\n'));
console.log('\n' + summary.join('\n'));
if (failures.length) process.exitCode = 1;
