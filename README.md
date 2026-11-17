# M-Natsir — Content Website and Admin CMS

A Laravel + Livewire site with public content, an authenticated admin area, and dedicated SEO/performance work.

**Public demo:** Not currently provided. Review the code on GitHub or follow the local setup instructions below. This repository is a portfolio project, not a claim of production deployment.

## What it demonstrates

| Area | Evidence in source code |
| --- | --- |
| Public content | Home, About, Articles, Topic, Library and Media Livewire routes (`routes/web.php`) |
| Admin area | Protected login and admin dashboard routes (`routes/web.php`) |
| SEO | Sitemap, robots, metadata and structured-data implementation with related tests |
| Quality evidence | Dedicated tests for admin authentication, content CRUD, SEO metadata and performance (`tests/Feature`) |
| Documentation | Specific engineering notes under `docs/` including `docs/admin-content-crud.md` |

## Stack

PHP, Laravel 13, Livewire, Blade, Tailwind CSS, Vite, Pest

## Architecture

```text
Browser
  -> UI (React/Inertia or Blade/Livewire, depending on this project)
  -> Laravel routes / middleware
  -> Controllers or Livewire components
  -> Eloquent models and database
```

Refer to `routes/web.php` and `app/` for this project's implementation details.

## Local development

Requirements: a compatible PHP/Composer environment, Node.js/npm, and a database configured for Laravel.

```bash
git clone https://github.com/Maximust27/M-Natsir.git
cd M-Natsir
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure your `.env` database settings and run:

```bash
php artisan migrate
php artisan serve
```

In another terminal run `npm run dev`. Run tests with `php artisan test` and check production asset compilation using `npm run build`.

**Verification:** These commands are a standard starting point, not evidence that this repository was installed or tested during this documentation change. Project-specific seed data or configuration may be needed. Do not commit secrets or real personal data.

## Source code walkthrough

- [`routes/web.php`](./routes/web.php)
- [`tests/Feature/AdminContentCrudTest.php`](./tests/Feature/AdminContentCrudTest.php)
- [`tests/Feature/TechnicalSeoTest.php`](./tests/Feature/TechnicalSeoTest.php)
- [`docs/admin-content-crud.md`](./docs/admin-content-crud.md)
- [`docs/performance.md`](./docs/performance.md)

Read [PORTFOLIO_CASE_STUDY.md](./docs/PORTFOLIO_CASE_STUDY.md) for engineering decisions, current limitations, and proposed verification tasks.

## Engineering next steps

- Avoid publishing production admin credentials or private content. Performance and SEO claims should cite actual dated results only after rerunning the audits.
- Review and run existing Pest tests; validate the current SEO/performance results rather than presenting unverified numeric improvements.
- Capture screenshots using fictional sample data and record a short walkthrough.
- Add a verified demo and reproducible setup instructions when available.

## Recruiter note

The documentation describes code that is visible in this repository. It does **not** claim real deployments, production users, validated performance gains, or a completed security assessment. Any team contribution should be described accurately before including it on a CV.

**Maintained by:** [@Maximust27](https://github.com/Maximust27) · [Portfolio](https://maximust27.github.io/)
