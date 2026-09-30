# Contact Page Implementation Plan

**Goal:** Build the dedicated `/contact` Livewire page from the approved Contact spec, with browser-side Web3Forms delivery, graceful unconfigured states for email/WhatsApp/CV, and consistent navigation/footer styling.

**Branch:** `feat/contact-livewire`  
**Base:** `main`  
**Spec:** `docs/superpowers/specs/2026-09-30-contact-page-design.md`

## Task 1 — Define the Contact page contract with tests

Files:
- Create `tests/Feature/Livewire/ContactTest.php`
- Modify `routes/web.php`

Tests first:
- `/contact` renders successfully and mounts `App\Livewire\Contact`.
- Hero, form fields, Media Guidelines, WhatsApp, and Professional CV content render.
- Header/footer/home CTA point to `route('contact')`.
- Footer About link is no longer uniquely bold.
- Required fields validate and unsupported purposes fail validation.
- Email-disabled state renders setup copy and blocks submission.
- Optional message is accepted.

Run targeted test and confirm RED before implementation.

## Task 2 — Add Web3Forms client configuration

Files:
- Create `config/contact.php`
- Modify `.env.example`
- Extend `tests/Feature/Livewire/ContactTest.php`

Configuration:
- `WEB3FORMS_ACCESS_KEY`
- `CONTACT_WHATSAPP_URL`
- `CONTACT_CV_URL`

Service behavior:
- `configured(): bool`
- Submit from browser JavaScript to `https://api.web3forms.com/submit`.
- Send the visitor name, email, institution, purpose, and optional message as browser form fields.
- Render the Web3Forms access key in the client form so Web3Forms can route to its configured destination email.
- Subject `[Website Contact] <Purpose> — <Name>`.
- Return clean success/failure result; never expose provider details.

Tests verify rendered client behavior and never call the live network.

## Task 3 — Implement Livewire Contact form behavior

Files:
- Create `app/Livewire/Contact.php`
- Extend `tests/Feature/Livewire/ContactTest.php`

Public state:
- `name`
- `email`
- `institution`
- `purpose`
- `message`
- honeypot field
- submission feedback state

Behavior:
- Server-side validation.
- Purpose allowlist.
- Mail-unconfigured guard.
- Honeypot reject.
- Provider success resets fields and shows success copy.
- Provider failure preserves input and shows generic retry copy.

Run targeted tests until GREEN.

## Task 4 — Build the Figma-aligned Contact page

Files:
- Create `resources/views/livewire/contact.blade.php`
- Extend `tests/Feature/Livewire/ContactTest.php`

Layout:
- Editorial hero: `Hubungi M. Natsir Kongah`.
- Desktop two-column layout, form left and utility cards right.
- Mobile single-column layout.
- Form fields: name, institutional email, institution/media, contact purpose, optional message/deadline.
- Disabled submit + `Layanan pengiriman sedang disiapkan.` when mail is not configured.
- Success/error live region.
- Media Guidelines always visible.
- WhatsApp active only when configured; otherwise `Segera tersedia` with no dead href.
- `Professional CV` replaces Press Kit; active only when configured; otherwise `CV segera tersedia`.
- Reuse existing Tailwind typography, borders, spacing, buttons, focus states, and `site-container` patterns.

## Task 5 — Integrate Contact navigation and Home teaser

Files:
- Modify `resources/views/components/site/header.blade.php`
- Modify `resources/views/components/site/footer.blade.php`
- Modify `resources/views/livewire/home.blade.php`
- Extend `tests/Feature/Livewire/ContactTest.php`

Changes:
- Desktop/mobile Contact -> `route('contact')` + `wire:navigate`.
- Home `Inquiries & Consultations` CTA -> `/contact`.
- Footer Contact -> `/contact`.
- Remove special `font-semibold text-ink` treatment from About so all footer nav items are consistent.

## Task 6 — CI, verification, and handoff

Files:
- Update existing verification workflow only if the feature branch is not covered.

Verification:
- `php artisan test tests/Feature/Livewire/ContactTest.php`
- `php artisan test`
- `npm run build`
- Verify no API key appears in rendered HTML.
- Verify configured/unconfigured WhatsApp and CV states.
- Verify the rendered Web3Forms endpoint, access key, fields, honeypot, and client-side feedback contract.
- Verify desktop/mobile page structure and keyboard focus markup.
- Compare `main...feat/contact-livewire` for scope creep.
- Confirm GitHub Actions green on latest branch head.

## Delivery

Keep all implementation on `feat/contact-livewire`. Do not merge into `main` until the user audits the branch and explicitly asks to merge.
