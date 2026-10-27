module.exports = {
    ci: {
        collect: {
            // Mobile emulation; the HTTP smoke audit covers all sitemap URLs.
            url: [
                'http://127.0.0.1:8000/',
                'http://127.0.0.1:8000/tentang',
                'http://127.0.0.1:8000/articles',
                'http://127.0.0.1:8000/topik/anti-pencucian-uang',
            ],
            numberOfRuns: 2,
            settings: {
                onlyCategories: ['performance', 'accessibility', 'best-practices', 'seo'],
                chromeFlags: '--no-sandbox --disable-dev-shm-usage',
            },
        },
        assert: {
            // Establish baselines as warnings; enforce deterministic SEO essentials.
            assertions: {
                'document-title': 'error',
                'meta-description': 'error',
                'html-has-lang': 'error',
                'is-crawlable': 'error',
                'categories:performance': ['warn', { minScore: 0.65 }],
                'categories:accessibility': ['warn', { minScore: 0.9 }],
                'categories:best-practices': ['warn', { minScore: 0.9 }],
                'categories:seo': ['warn', { minScore: 0.95 }],
                'largest-contentful-paint': ['warn', { maxNumericValue: 2500 }],
                'cumulative-layout-shift': ['warn', { maxNumericValue: 0.1 }],
                'total-blocking-time': ['warn', { maxNumericValue: 200 }],
            },
        },
        upload: {
            // Keep report artifacts in GitHub rather than public temporary storage.
            target: 'filesystem',
            outputDir: './reports/lighthouse',
        },
    },
};
