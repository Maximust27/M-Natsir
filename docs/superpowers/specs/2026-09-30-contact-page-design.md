# Contact Page Design

**Date:** 2026-09-30  
**Branch:** `feat/contact-livewire`

## Goal

Add a dedicated `/contact` page that follows the approved Figma contact layout while fitting the existing editorial visual system of the M. Natsir Kongah site.

The page must be useful before email, WhatsApp, and CV details are available. Missing credentials or assets must degrade gracefully instead of producing dead links or runtime errors. When email configuration is later supplied, the same page becomes a working professional contact form without redesigning the UI.

## User Intent and Success Criteria

The contact experience should:

- look consistent with the approved Figma layout and the existing Home, About, Articles, Library, and Media pages;
- provide one clear professional contact form for journalists, academics, consultants, and related inquiries;
- use a reputable transactional-email provider through the Laravel backend;
- keep secrets and personal contact details out of Blade templates and source control;
- allow WhatsApp and CV links to be added later by configuration only;
- remain visually complete when those integrations are not yet configured;
- make the header and footer Contact links point to the dedicated page;
- remove the current footer inconsistency where About is visually bolder than the neighboring navigation links.

## Scope

### Included

- top-level route `/contact`;
- class-based Livewire page `App\Livewire\Contact`;
- contact form based on the approved Figma layout;
- Laravel-side validation;
- email delivery through the Resend HTTP API;
- disabled/unavailable state while email configuration is incomplete;
- basic honeypot protection and rate limiting;
- success and failure feedback;
- right-side Media Guidelines panel;
- optional WhatsApp action;
- Professional CV card replacing the Figma Press Kit card;
- header, mobile navigation, footer, and Home contact teaser updates;
- feature tests for page rendering, form behavior, provider integration, configuration fallbacks, and navigation.

### Excluded

- storing inquiries in a database;
- admin dashboard or inbox;
- newsletter subscription;
- file attachments from visitors;
- CAPTCHA in the first version;
- CRM integration;
- analytics tracking specific to the contact form;
- automatic calendar booking;
- a full media/press kit package.

## Information Architecture

Add:

- `/contact` -> `App\Livewire\Contact`
- route name: `contact`

Update:

- desktop header Contact button -> `route('contact')`
- mobile Contact button -> `route('contact')`
- footer Contact link -> `route('contact')`
- Home contact teaser button -> `route('contact')`

The existing Home contact teaser can remain as a compact entry point. The dedicated page becomes the canonical contact destination.

## Visual Structure

The page should closely follow the supplied Figma contact screen without creating a separate design language.

### Hero

Use the existing `site-container` and editorial spacing system.

Content:

- heading: `Hubungi M. Natsir Kongah`
- supporting copy:
  `Pusat pertanyaan profesional untuk jurnalis, akademisi, dan konsultan kepatuhan yang mencari keahlian dalam intelijen keuangan dan kejahatan kerah putih.`

The hero should be spacious and text-led, matching the Figma's restrained editorial presentation.

### Main Content

Desktop:

- two-column layout;
- wider left column for the contact form;
- narrower right column for guidance and secondary actions.

Mobile:

- single column;
- form first;
- guidance, WhatsApp, and CV cards below;
- no horizontal overflow;
- all controls at touch-friendly sizes.

## Contact Form

Section heading:

- `Form Komunikasi`

Fields:

1. `Nama Lengkap`
2. `Email Kerja Institusi`
3. `Institusi / Media`
4. `Tujuan Kontak`
5. `Pesan & Tenggat Waktu (Deadline)` — optional

### Contact Purpose Options

Use a controlled select with these values:

- Media / Interview
- Academic / Research
- Consultation / Compliance
- Speaking / Event
- Other

Values should be stable internal keys while labels remain human-readable.

### Validation

Server-side validation is authoritative.

Recommended rules:

- name: required, string, max 120;
- email: required, valid email, max 190;
- institution: required, string, max 190;
- purpose: required, one of the supported purpose keys;
- message: nullable, string, max 3000;
- honeypot field: must remain empty.

Validation errors render inline next to the relevant field and preserve submitted values.

## Email Delivery Architecture

Use Resend through Laravel's built-in HTTP client rather than placing third-party JavaScript in the browser.

Create a small isolated service, for example:

- `App\Services\ContactMailer`

Responsibilities:

- determine whether mail delivery is configured;
- build the Resend request payload;
- call the Resend API using the configured secret;
- return a clear success/failure result to the Livewire component;
- never expose the API key to client-side markup.

This keeps provider-specific code out of the Livewire component and makes a future provider swap localized.

### Configuration

Add a dedicated config file such as `config/contact.php`.

Environment values:

```env
CONTACT_EMAIL_TO=
CONTACT_EMAIL_FROM=
CONTACT_EMAIL_FROM_NAME="M. Natsir Kongah"
RESEND_API_KEY=
CONTACT_WHATSAPP_URL=
CONTACT_CV_URL=
```

No real email address, API key, WhatsApp number, or private URL is committed to Git.

Mail is considered enabled only when all required mail values are present:

- `CONTACT_EMAIL_TO`
- `CONTACT_EMAIL_FROM`
- `RESEND_API_KEY`

### Email Payload

The outgoing message should contain:

- sender name;
- sender email;
- institution/media;
- purpose;
- optional message/deadline;
- timestamp.

The site-controlled verified sender goes in Resend's `from` field. The visitor email should be used as `reply_to`, not as the sender, to avoid deliverability and spoofing issues.

Suggested subject format:

`[Website Contact] <Purpose> — <Name>`

## Email-Unavailable State

Because production mail credentials do not exist yet, the page must remain usable visually without pretending that a message can be delivered.

When mail configuration is incomplete:

- all form fields may still render;
- the submit button remains visible but disabled;
- supporting text states: `Layanan pengiriman sedang disiapkan.`;
- no HTTP request to Resend is attempted;
- the page does not throw configuration exceptions.

Once the environment variables are added and Laravel config is refreshed, the same button becomes active automatically.

## Spam and Abuse Protection

First version should stay lightweight.

### Honeypot

Include a visually hidden field that normal visitors never fill. A non-empty value prevents submission.

### Rate Limiting

Rate-limit submissions by request IP, with a conservative default such as:

- 3 successful attempts per 10 minutes.

When the limit is reached, show a generic Indonesian message asking the visitor to try again later.

Do not disclose internal rate-limit keys or provider details.

## Submission States

### Success

After Resend confirms delivery:

- show a clear success notice;
- reset the form;
- keep the page in place;
- do not redirect away from Contact.

Suggested copy:

`Pesan berhasil dikirim. Terima kasih — permintaan Anda akan ditinjau sesuai prioritas dan konteksnya.`

### Provider Failure

If Resend returns an error or the request fails:

- do not clear user input;
- display a generic retry message;
- do not expose API responses, stack traces, or credentials.

Suggested copy:

`Pesan belum dapat dikirim. Silakan coba kembali beberapa saat lagi.`

## Right-Side Panels

### Media Guidelines

Match the Figma panel hierarchy.

Heading:

- `Panduan Media`

Content:

- response window: `24–48 jam untuk pertanyaan terverifikasi.`
- specialization: `AML, Kejahatan Kerah Putih, Intelijen Keuangan.`

This is informational and always visible.

### WhatsApp

Heading:

- `WhatsApp`

Purpose:

- fast confirmation for urgent news coordination or interview timing.

When `CONTACT_WHATSAPP_URL` is configured:

- show active external action: `Mulai Chat`;
- open safely in a new tab;
- use `rel="noopener noreferrer"`.

When not configured:

- keep the card;
- replace the action with a disabled `Segera tersedia` state;
- do not render `href="#"` or another dead link.

### Professional CV

Replace the Figma `Press Kit` block with:

- heading: `Professional CV`;
- description focused on professional profile, experience, and areas of expertise;
- action: `Download CV`.

When `CONTACT_CV_URL` is configured:

- render an active download/open action;
- URLs may point to a local public file or an external stable URL.

When not configured:

- keep the dark visual card;
- render a disabled `CV segera tersedia` state.

Recommended future local asset location:

`public/files/m-natsir-kongah-cv.pdf`

Then configure:

`CONTACT_CV_URL=/files/m-natsir-kongah-cv.pdf`

This gives the user one predictable place to replace the PDF later.

## Shared Navigation and Footer Consistency

### Header

The Contact button should use:

- `route('contact')`;
- active state on the Contact page where appropriate;
- `wire:navigate` consistently with other internal routes.

### Footer

All primary site-navigation links should use the same visual weight.

Remove the special bold treatment currently applied only to About. Articles, Library, About, Media, and Contact should share the same default typography and hover behavior.

No single footer navigation item should appear permanently active or emphasized.

## Home Page Contact Teaser

The current Home `Inquiries & Consultations` card can remain, but it should become a teaser for the dedicated Contact page.

Change its CTA from the self-referencing `#contact` link to `route('contact')`.

The Home section does not need to duplicate the full form.

## Components and File Boundaries

Expected new files:

- `app/Livewire/Contact.php`
- `app/Services/ContactMailer.php`
- `resources/views/livewire/contact.blade.php`
- `config/contact.php`
- `tests/Feature/Livewire/ContactTest.php`

Potential mail-specific test/support files may be added only if they improve isolation.

Expected modified files:

- `routes/web.php`
- `resources/views/components/site/header.blade.php`
- `resources/views/components/site/footer.blade.php`
- `resources/views/livewire/home.blade.php`
- `.env.example`
- existing GitHub Actions workflow if the new feature branch is not already covered.

Do not introduce a new UI component system unless repeated markup clearly justifies it.

## Accessibility

- every form control has a visible label;
- validation messages are associated with the relevant field;
- submit status uses an appropriate live region;
- disabled integration states remain legible and distinguishable;
- keyboard focus follows existing site focus-ring patterns;
- color is not the only indicator of an error or disabled action;
- external actions include accessible labels where necessary.

## Security and Privacy

- secrets remain server-side only;
- no API key is written into Blade, JavaScript, repository history, or browser-visible state;
- form values are treated as untrusted input;
- user-supplied content is escaped in rendered feedback;
- Resend request failures are logged without logging secrets;
- do not persist contact form submissions in the first version;
- do not expose visitor email addresses in URLs.

## Testing Strategy

Create `tests/Feature/Livewire/ContactTest.php` covering at least:

- `/contact` renders successfully;
- Contact Livewire component mounts;
- hero/form/right-side content renders;
- header and footer point to the named Contact route;
- footer About is no longer uniquely bold;
- required fields validate;
- unsupported purpose values are rejected;
- optional message can be omitted;
- mail-disabled state prevents submission and shows setup copy;
- honeypot blocks delivery;
- rate limiting blocks excessive submissions;
- successful provider response shows success feedback and resets fields;
- provider failure shows retry feedback and preserves input;
- visitor email is sent as `reply_to`;
- API key is never rendered into the page;
- WhatsApp configured/unconfigured states render correctly;
- CV configured/unconfigured states render correctly;
- Home contact CTA points to `/contact`.

Use Laravel HTTP fakes for Resend requests. Tests must not perform real network calls.

## Verification

Before handoff:

- run the targeted Contact tests;
- run the full Laravel test suite;
- run `npm run build`;
- verify the Contact page on desktop and mobile widths;
- verify tab/focus behavior;
- verify all unavailable states;
- verify no secret appears in rendered HTML;
- verify the feature branch is green in GitHub Actions.

## Delivery

All implementation work remains on:

`feat/contact-livewire`

Do not merge to `main` until the user has pulled, audited, and explicitly approved the implementation.
