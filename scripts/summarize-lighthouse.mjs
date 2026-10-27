/**
 * Summarize the representative Lighthouse CI runs in the GitHub Actions summary.
 * Lab LCP/CLS/TBT are not real-user Core Web Vitals field measurements.
 */
import { readFile, writeFile, appendFile, mkdir } from 'node:fs/promises';
import { resolve, relative } from 'node:path';

const dir = resolve('reports/lighthouse');
const manifest = JSON.parse(await readFile(resolve(dir, 'manifest.json'), 'utf8'));
const representatives = manifest.filter((entry) => entry.isRepresentativeRun);
const runs = representatives.length > 0 ? representatives : manifest;
const rows = [];
const data = [];

const score = (number) => typeof number === 'number' ? Math.round(number * 100) : '–';
const seconds = (number) => typeof number === 'number' ? (number / 1000).toFixed(2) + ' s' : '–';
const cls = (number) => typeof number === 'number' ? number.toFixed(3) : '–';

for (const entry of runs) {
    const report = JSON.parse(await readFile(entry.jsonPath, 'utf8'));
    const path = new URL(report.finalUrl || entry.url).pathname;
    const categories = report.categories ?? {};
    const audits = report.audits ?? {};

    const row = {
        path,
        performance: categories.performance?.score ?? null,
        accessibility: categories.accessibility?.score ?? null,
        bestPractices: categories['best-practices']?.score ?? null,
        seo: categories.seo?.score ?? null,
        lcpMs: audits['largest-contentful-paint']?.numericValue ?? null,
        cls: audits['cumulative-layout-shift']?.numericValue ?? null,
        tbtMs: audits['total-blocking-time']?.numericValue ?? null,
        jsonReport: relative(process.cwd(), entry.jsonPath),
        htmlReport: relative(process.cwd(), entry.htmlPath),
    };
    data.push(row);

    rows.push('| ' + row.path + ' | ' + score(row.performance) +
        ' | ' + score(row.accessibility) + ' | ' + score(row.bestPractices) +
        ' | ' + score(row.seo) + ' | ' + seconds(row.lcpMs) +
        ' | ' + cls(row.cls) + ' | ' + seconds(row.tbtMs) + ' |');
}

const markdown = [
    '## Lighthouse CI — mobile lab baseline',
    '',
    'Scores use a 0–100 scale. LCP, CLS, TBT are lab values from GitHub Chrome, not production field data.',
    '',
    '| Path | Performance | Accessibility | Best practices | SEO | LCP | CLS | TBT |',
    '|---|---:|---:|---:|---:|---:|---:|---:|',
    ...rows,
    '',
    'Full JSON and HTML reports are attached to this workflow run.',
    '',
];

await mkdir(resolve('reports'), { recursive: true });
await writeFile(resolve('reports/lighthouse-summary.json'), JSON.stringify(data, null, 2) + '\n');
await writeFile(resolve('reports/lighthouse-summary.md'), markdown.join('\n'));
if (process.env.GITHUB_STEP_SUMMARY) {
    await appendFile(process.env.GITHUB_STEP_SUMMARY, '\n' + markdown.join('\n'));
}
console.log(markdown.join('\n'));
