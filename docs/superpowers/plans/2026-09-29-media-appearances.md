# Media & Appearances Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a verified, searchable, filterable `/media` archive for M. Natsir Kongah that is visually and behaviorally consistent with Articles and Library.

**Architecture:** Add one class-based Livewire page with static curated appearance data, a dedicated reusable appearance-card Blade component, and reuse the existing shared UI primitives for filters, badges, cards, and buttons. Keep source attribution explicit, support optional thumbnails with text fallback, and wire the page into desktop/mobile navigation and the footer.

**Tech Stack:** Laravel, Livewire, Blade, Tailwind CSS, Alpine.js, Pest/PHPUnit, GitHub Actions.

**Spec:** `docs/superpowers/specs/2026-09-29-media-appearances-design.md`

## Global Constraints

- Work only on `feat/media-livewire`; do not merge to `main`.
- Only include strict appearances where M. Natsir Kongah has an explicit active role in the source.
- Exclude press-contact-only mentions, authored opinion pieces, Library material, and duplicate coverage of one event.
- Reuse the existing site typography, color palette, spacing, border, focus, and responsive patterns.
- Reuse `x-ui.filter-chip`, `x-ui.card`, `x-ui.badge`, and `x-ui.button`; do not introduce a parallel UI system.
- Use real source-linked thumbnails only when trustworthy and stable; otherwise render the text-first fallback.
- Run the full Laravel test suite and frontend build before the branch is presented as ready for audit.

## Review Focus

- Unknown or malformed category values must fall back safely without breaking the page.
- Search with whitespace/case differences must still match title, outlet, role, topic, year, and summary.
- Items without thumbnails must render a complete card without an empty media box.
- External links must preserve `target="_blank"` and `rel="noopener noreferrer"`.
- Header/mobile/footer navigation must point to `route('media')` without breaking existing routes.

---

### Task 1: Define the Media route and Livewire behavior with failing tests

**Files:**
- Create: `tests/Feature/Livewire/MediaTest.php`
- Modify: `routes/web.php`

**Interfaces:**
- Consumes: existing Laravel route and Livewire testing conventions from Articles/Library.
- Produces: route name `media`, URL `/media`, and expected public component state: `search`, `activeCategory`, `selectCategory(string)`, `clearFilters()`.

- [ ] **Step 1: Write failing route/render/filter/search tests**

Cover these assertions in `MediaTest.php`:

- `GET route('media')` is 200, contains `MEDIA & APPEARANCES`, and mounts `App\Livewire\Media`.
- category `interviews` shows an interview/broadcast item and excludes a media-visit item.
- search matches an outlet or role term and excludes unrelated entries.
- unknown category leaves/falls back to `all`.
- search + category reset via `clearFilters()` returns to defaults.
- a query with no matches shows the empty-state copy.
- an entry known to have no thumbnail still renders its title and action.

- [ ] **Step 2: Run the targeted test to verify red**

Run: `php artisan test tests/Feature/Livewire/MediaTest.php`

Expected: FAIL because the Media route/component does not exist yet.

- [ ] **Step 3: Add route declaration only**

In `routes/web.php`, import `App\Livewire\Media` and add:

`Route::livewire('/media', Media::class)->name('media');`

- [ ] **Step 4: Re-run targeted test**

Run: `php artisan test tests/Feature/Livewire/MediaTest.php`

Expected: still FAIL because `App\Livewire\Media` is not implemented.

- [ ] **Step 5: Commit the red contract**

```bash
git add routes/web.php tests/Feature/Livewire/MediaTest.php
git commit -m "test: define media archive behavior"
```

### Task 2: Implement the Media Livewire component and verified archive data

**Files:**
- Create: `app/Livewire/Media.php`
- Test: `tests/Feature/Livewire/MediaTest.php`

**Interfaces:**
- Consumes: route `media` from Task 1.
- Produces:
  - `public string $search = ''`
  - `public string $activeCategory = 'all'`
  - `public function selectCategory(string $category): void`
  - `public function clearFilters(): void`
  - view data: `categories`, `appearances`, `featuredAppearance`, `resultCount`, and category/stat counts.

- [ ] **Step 1: Add/adjust tests for the strict archive data**

Pin representative entries from each category after re-verifying their canonical sources:

- Interviews & Broadcast
- Talks & Panels
- Media Visits
- Recognition

Also assert that press-contact-only text is not represented as an archive entry.

- [ ] **Step 2: Verify the added assertions are red**

Run: `php artisan test tests/Feature/Livewire/MediaTest.php`

Expected: FAIL because the component/data do not exist.

- [ ] **Step 3: Implement `App\Livewire\Media`**

Use:

- `#[Layout('layouts::app')]`
- `#[Title('Media & Appearances | M. Natsir Kongah')]`
- URL-backed `q` and `category` properties like Articles.
- category keys: `all`, `interviews`, `talks`, `media-visits`, `recognition`.
- category definitions with label, inactive classes, and counts.
- case-insensitive trimmed search across title, outlet, role, topic labels, year/date, and summary.
- safe category validation before filtering.
- one deterministic featured appearance selected from a recent, strongly sourced archive item.
- curated source-backed records only; preserve canonical/source URLs and action labels.

- [ ] **Step 4: Run targeted tests**

Run: `php artisan test tests/Feature/Livewire/MediaTest.php`

Expected: component/data assertions advance to view-related failures only, or PASS if the minimal view contract is already satisfied.

- [ ] **Step 5: Commit**

```bash
git add app/Livewire/Media.php tests/Feature/Livewire/MediaTest.php
git commit -m "feat: add verified media archive data"
```

### Task 3: Build the Media page and reusable appearance card

**Files:**
- Create: `resources/views/livewire/media.blade.php`
- Create: `resources/views/components/media/appearance-card.blade.php`
- Test: `tests/Feature/Livewire/MediaTest.php`

**Interfaces:**
- Consumes: `categories`, `appearances`, `featuredAppearance`, `resultCount`, `search`, and `activeCategory` from Task 2.
- Produces: responsive editorial page with hero, featured item, search/filter controls, archive grid, empty state, and hybrid thumbnail/text cards.

- [ ] **Step 1: Add view-level assertions**

Assert the rendered page includes:

- `Public Record`
- `MEDIA & APPEARANCES`
- filter labels from all four strict categories
- featured appearance treatment
- empty-state reset control
- representative external action label such as `Watch`, `Read Coverage`, or `View Event`

- [ ] **Step 2: Run tests to verify red**

Run: `php artisan test tests/Feature/Livewire/MediaTest.php`

Expected: FAIL because the Blade page/card are missing.

- [ ] **Step 3: Implement `x-media.appearance-card`**

Use `x-ui.card`, `x-ui.badge`, and `x-ui.button`. Support optional `thumbnail`; when null, omit the image region entirely. Render category, outlet/event, date, role, summary, topic chips, optional source note, and external CTA.

- [ ] **Step 4: Implement `livewire/media.blade.php`**

Mirror Articles/Library conventions:

- editorial hero and compact stats
- featured appearance block
- search input
- horizontally scrollable `x-ui.filter-chip` controls
- responsive two-column archive grid where appropriate
- `wire:loading.class="opacity-60"`
- bordered empty state with reset button
- no new typography/color/radius system

- [ ] **Step 5: Run targeted tests**

Run: `php artisan test tests/Feature/Livewire/MediaTest.php`

Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add resources/views/livewire/media.blade.php resources/views/components/media/appearance-card.blade.php tests/Feature/Livewire/MediaTest.php
git commit -m "feat: build media appearances page"
```

### Task 4: Integrate Media into shared navigation and CI

**Files:**
- Modify: `resources/views/components/site/header.blade.php`
- Modify: `resources/views/components/site/footer.blade.php`
- Modify: `.github/workflows/verify-articles.yml`
- Test: `tests/Feature/Livewire/MediaTest.php`

**Interfaces:**
- Consumes: named route `media`.
- Produces: desktop/mobile active Media navigation, footer Media link, and branch CI coverage for `feat/media-livewire`.

- [ ] **Step 1: Add navigation assertions**

In `MediaTest.php`, assert the Media page markup contains the `/media` link and active navigation state while preserving Articles and Library links.

- [ ] **Step 2: Run targeted tests to verify red**

Run: `php artisan test tests/Feature/Livewire/MediaTest.php`

Expected: FAIL until navigation is updated.

- [ ] **Step 3: Update shared navigation**

Replace the existing Media links to Home contact anchors with `route('media')`, apply `request()->routeIs('media')`, and keep `wire:navigate` consistent with the other top-level pages.

Update the footer's Media hub link to `route('media')` while keeping Contact as the contact destination where it still semantically belongs.

- [ ] **Step 4: Extend GitHub Actions branch trigger**

Add `feat/media-livewire` to the existing `push.branches` list in `.github/workflows/verify-articles.yml`.

- [ ] **Step 5: Run targeted tests**

Run: `php artisan test tests/Feature/Livewire/MediaTest.php`

Expected: PASS.

- [ ] **Step 6: Commit**

```bash
git add resources/views/components/site/header.blade.php resources/views/components/site/footer.blade.php .github/workflows/verify-articles.yml tests/Feature/Livewire/MediaTest.php
git commit -m "feat: integrate media page navigation"
```

### Task 5: Whole-branch verification and audit handoff

**Files:**
- No planned product-code changes unless verification reveals a defect.

**Interfaces:**
- Consumes: all prior tasks.
- Produces: green branch ready for the user to pull and audit.

- [ ] **Step 1: Run the full Laravel test suite**

Run: `php artisan test`

Expected: PASS with no failures.

- [ ] **Step 2: Run the frontend build**

Run: `npm run build`

Expected: build exits successfully.

- [ ] **Step 3: Review the branch diff**

Compare `main...feat/media-livewire` and verify only the spec/plan plus expected Media, navigation, test, route, and CI files changed.

- [ ] **Step 4: Verify CI on the branch**

Confirm the GitHub Actions run for `feat/media-livewire` completes successfully after the final push.

- [ ] **Step 5: Request whole-branch code review**

Use the code-review workflow to check source provenance, filter/search behavior, component reuse, navigation consistency, responsive markup, and accidental scope creep.

- [ ] **Step 6: Hand off for local audit**

Provide:

```bash
git fetch origin
git switch feat/media-livewire
git pull --ff-only origin feat/media-livewire
```

Do not merge to `main`.
