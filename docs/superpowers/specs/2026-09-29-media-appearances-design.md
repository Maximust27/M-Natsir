# Media & Appearances Design

Date: 2026-09-29  
Branch: `feat/media-livewire`

## Goal

Add a top-level `/media` page for verified public appearances by M. Natsir Kongah while keeping the visual language and interaction patterns consistent with Home, About, Articles, and Library.

The page is an editorial archive, not a general news feed.

## Scope

### Included

Only appearances where M. Natsir Kongah is explicitly documented as one of the following:

- interviewee or media source in a substantive interview
- speaker or seminar presenter
- panelist
- participant in a documented media visit
- representative receiving a public recognition or award
- other public appearance where his active role is explicit in the source

### Excluded

- press releases that only list him as media contact
- articles that merely mention his name without a substantive appearance
- authored opinion pieces that belong in Articles
- papers, archival writings, institutional publications, and research references that belong in Library
- duplicated coverage of the same event as separate archive entries

When multiple outlets cover the same appearance, the page should keep one canonical event entry and may preserve supporting coverage links in metadata if needed later.

## Information Architecture

Create a new route:

- `/media` -> `App\Livewire\Media`
- route name: `media`

Update desktop and mobile navigation so the existing Media item points to `route('media')` and receives active-state styling.

Update the footer's media-related navigation to link to the Media page rather than the Home contact section where appropriate.

## Page Structure

### 1. Hero

Use the same editorial composition as Articles and Library:

- mono eyebrow
- large serif heading
- short descriptive paragraph
- compact archive statistics

Suggested copy:

- eyebrow: `Public Record`
- heading: `MEDIA & APPEARANCES`
- description: `Wawancara, forum publik, media visit, dan penampilan profesional M. Natsir Kongah yang dapat ditelusuri ke sumber terbuka.`

Statistics should summarize the archive without introducing a new visual system.

### 2. Featured Appearance

Show one featured appearance near the top of the page.

Selection rule:

- prefer a strong, recent, well-sourced appearance with a meaningful role
- do not label an item as featured because of inferred importance
- featured is a presentation choice, not an evaluative ranking of the person or outlet

The featured block may use a larger thumbnail when an official visual source is available. Otherwise it must fall back to a text-forward layout without leaving an empty image area.

### 3. Search and Filters

Reuse existing site interaction patterns.

Search should match:

- title
- outlet or event
- role
- topic
- year
- summary

Category filter chips:

- All
- Interviews & Broadcast
- Talks & Panels
- Media Visits
- Recognition

Use `x-ui.filter-chip` for category controls.

### 4. Archive Grid

Use a reusable component:

- `resources/views/components/media/appearance-card.blade.php`

Cards should use or compose existing shared UI primitives:

- `x-ui.card`
- `x-ui.badge`
- `x-ui.button`

Each card should support:

- category
- title
- date
- outlet or event
- role
- short summary
- topics
- canonical source URL
- source label
- action label
- optional thumbnail
- optional supporting source note

Possible action labels:

- Watch
- Read Coverage
- View Event

### 5. Hybrid Thumbnail Strategy

Use thumbnail + text when there is a trustworthy, usable visual tied to the official or canonical source.

If no suitable image is available:

- render a text-first card
- preserve the same card height rhythm where practical
- do not generate a fake event thumbnail
- do not use generic stock imagery

This avoids inconsistent visual quality while keeping the page richer when real media assets exist.

## Data Model

The first version can follow the existing static-data pattern used by Articles and Library inside the Livewire class.

Each appearance should expose fields similar to:

```php
[
    'title' => '',
    'category' => '',
    'categoryLabel' => '',
    'date' => '',
    'year' => 2025,
    'outlet' => '',
    'role' => '',
    'summary' => '',
    'topics' => [],
    'url' => '',
    'actionLabel' => '',
    'thumbnail' => null,
    'sourceNote' => null,
]
```

The implementation should keep presentation labels in data where that improves reuse, but avoid duplicating styling decisions in every record.

## Initial Archive

Seed the first version from the strict audit already agreed in conversation.

Candidate groups include:

- RRI interviews on online gambling and related financial-crime issues
- CNBC Indonesia interview or broadcast appearance
- tvOne broadcast appearance
- MetroTV or Medcom coverage where he is a substantive source
- PPATK media visits to The Jakarta Post and Pikiran Rakyat
- public seminars, panels, and sharing sessions where he is named as speaker or panelist
- public recognition appearances where he is documented as representing PPATK

Before an item is added, verify that the source explicitly supports his active role.

Do not bulk-import pages that only identify him as a press contact.

## Styling and Consistency

The Media page must feel native to the existing site.

Reuse:

- `site-container`
- existing border and paper treatments
- serif headings
- mono metadata labels
- existing slate/ink/accent palette
- existing spacing scale
- existing focus states
- existing responsive behavior

Do not introduce a new color system, card radius system, typography family, or unrelated animation language.

## Responsive Behavior

- desktop: featured layout may split media and copy into columns
- tablet: archive cards in two columns where content width allows
- mobile: single-column cards and horizontally scrollable filter chips
- thumbnail blocks must preserve aspect ratio and never force horizontal overflow
- buttons and filter targets must remain touch-friendly

## Empty and Loading States

Follow the existing Articles and Library patterns.

If filters produce no result:

- show a bordered empty state
- explain that no matching appearance was found
- provide a reset action

During Livewire updates:

- reduce archive opacity using the same loading treatment already used elsewhere

## Testing

Add a dedicated feature test file:

- `tests/Feature/Livewire/MediaTest.php`

Cover at minimum:

- `/media` renders successfully
- strict archive entries appear
- category filtering works
- search works across title, outlet, role, and topic
- unknown category values do not break the component
- empty state renders correctly
- reset behavior restores the full archive
- navigation contains the Media route and active state

Run the full Laravel test suite and frontend build before presenting the branch as ready for audit.

## Files Expected to Change

New:

- `app/Livewire/Media.php`
- `resources/views/livewire/media.blade.php`
- `resources/views/components/media/appearance-card.blade.php`
- `tests/Feature/Livewire/MediaTest.php`

Updated:

- `routes/web.php`
- `resources/views/components/site/header.blade.php`
- `resources/views/components/site/footer.blade.php`

Other files should only change if required by an existing shared component limitation discovered during implementation.

## Delivery

Implementation remains isolated on `feat/media-livewire`.

The branch will be ready for the user to pull and audit locally before any merge to `main`.

No merge to `main` is part of this task unless the user explicitly requests it after review.
