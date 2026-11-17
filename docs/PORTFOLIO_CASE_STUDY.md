# M-Natsir — Content Website and Admin CMS: Engineering Case Study

## Overview

A Laravel + Livewire site with public content, an authenticated admin area, and dedicated SEO/performance work.

**Context:** This project models an application domain rather than a documented commercial production deployment. Its value as a portfolio sample comes from how the workflows are implemented and tested.

## Implementation evidence

### Public content

Home, About, Articles, Topic, Library and Media Livewire routes (`routes/web.php`).

### Admin area

Protected login and admin dashboard routes (`routes/web.php`).

### SEO

Sitemap, robots, metadata and structured-data implementation with related tests.

### Quality evidence

Dedicated tests for admin authentication, content CRUD, SEO metadata and performance (`tests/Feature`).

### Documentation

Specific engineering notes under `docs/` including `docs/admin-content-crud.md`.


## Architecture

- **Stack:** PHP, Laravel 13, Livewire, Blade, Tailwind CSS, Vite, Pest
- **Routing and permissions:** Begin at [routes/web.php](../routes/web.php).
- **Application layer:** Inspect the linked controllers or Livewire components in the project README.
- **Persistence:** Eloquent models and migrations model the application's data and relationships.

## Code review entry points

- [`routes/web.php`](../routes/web.php)
- [`tests/Feature/AdminContentCrudTest.php`](../tests/Feature/AdminContentCrudTest.php)
- [`tests/Feature/TechnicalSeoTest.php`](../tests/Feature/TechnicalSeoTest.php)
- [`docs/admin-content-crud.md`](../docs/admin-content-crud.md)
- [`docs/performance.md`](../docs/performance.md)

## Risks and improvement opportunities

Avoid publishing production admin credentials or private content. Performance and SEO claims should cite actual dated results only after rerunning the audits.

### Testing

Review and run existing Pest tests; validate the current SEO/performance results rather than presenting unverified numeric improvements.

### Reproducibility

The repository was inspected remotely. Its tests were **not executed** as part of this documentation-only update. A clean installation, test run, security review, and a short recorded walkthrough are recommended before presenting this project as production-ready.

## How to present the project to recruiters

1. Define the user problem and what the project models.
2. Demonstrate a concrete end-to-end workflow using fictional data.
3. Explain one implementation tradeoff and locate the relevant code.
4. Show passing tests or state clearly which tests remain to be added.
5. Specify your exact personal contribution and any team collaboration honestly.

Avoid unsupported claims about business impact, usage statistics, uptime or performance.
