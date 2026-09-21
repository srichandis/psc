# Premier Specialist Clinic

Marketing website and staff admin panel for a specialist medical clinic in Coomera QLD.

Built with **Laravel 13**, **Tailwind CSS 4**, **Alpine.js** and **SQLite** — no database server required.

- **Public site** — homepage with the specialist directory, clinical specialties, patient journey and location/map; a standalone **About Us** page covering parking, what to bring, booking and referrals; a **profile page per doctor**; and an appointment booking form that writes to SQLite.
- **Admin panel** — password protected dashboard for triaging booking requests and managing the specialists and specialties shown on the website.

### Pages

| Route | View | Contents |
|---|---|---|
| `/` | `home.blade.php` | Hero, specialist directory, specialties, visit process, location/map |
| `/about` | `about.blade.php` | Location & parking, before your visit, booking, referrals, contact/urgent appointments |
| `/specialists/{slug}` | `specialists/show.blade.php` | Full doctor profile: biography, headed sections, special interests, consulting days, booking |

The About page is a separate route rather than an anchor on the homepage, and carries the
same navbar, footer and booking modal. The navbar marks it with `aria-current="page"` when
active, and every homepage anchor is written as `/#section` so it still navigates correctly
from `/about`.

### Specialist profile pages

Every card in the homepage directory — and every doctor listed in a specialty's detail
modal — links to that specialist's own page, at a readable slug URL such as
`/specialists/dr-sarah-tanusha-thomas`. The page is a real, linkable address rather than a
modal, so profiles can be shared, bookmarked and indexed by search engines; it also emits
Schema.org `Physician` JSON-LD. Bookings made from a profile pre-select that doctor.

Page content comes from three sources, so one doctor's page can be bullet-heavy while
another's is prose:

| Field | Rendered as |
|---|---|
| `bio` | The introduction (also the meta description) |
| `profile_sections` | Ordered headed blocks — `list` (bullets) or `paragraphs` |
| `special_interests` | The "Special Interests" cards |

The route binds on the **slug**, and a specialist hidden from the website returns 404
rather than being reachable by guessing a URL. Doctors sharing a department are linked at
the foot of each profile.

---

## Requirements

| Tool | Version |
|---|---|
| PHP | 8.3+ (tested on 8.4) |
| Composer | 2.x |
| Node.js | 20+ |
| PHP extensions | `pdo_sqlite`, `sqlite3` |

## Getting started

```bash
# 1. Install PHP and JavaScript dependencies
composer install
npm install

# 2. Create the environment file and application key
cp .env.example .env
php artisan key:generate

# 3. Create the SQLite database, schema and demo content
touch database/database.sqlite
php artisan migrate --seed

# 4. Build the front-end assets
npm run build
```

Then serve the site:

```bash
php artisan serve
```

Visit <http://localhost:8000>.

> `composer setup` performs the whole install in one go on a fresh clone.

### Working on styles or JavaScript

```bash
npm run dev     # Vite dev server with hot reloading
npm run build   # production build
```

---

## Admin panel

The staff area lives at **`/admin`** and requires a sign in.

The seeder creates a single account:

| | |
|---|---|
| **URL** | <http://localhost:8000/admin> |
| **Email** | `admin@premierspecialistclinic.com.au` |
| **Password** | `password` |

Override these with the `ADMIN_NAME`, `ADMIN_EMAIL` and `ADMIN_PASSWORD` environment
variables *before* seeding. **Change the password before deploying anywhere public.**

### What the panel does

| Screen | Purpose |
|---|---|
| **Dashboard** | Request volume for the last 7 days, pipeline breakdown, latest requests |
| **Appointments** | Filter by status, search by name/phone/email, inline status changes, detail view with internal notes, delete |
| **Specialists** | Add, edit, reorder, hide or remove practitioners, and build their profile pages section by section |
| **Specialties** | Manage departments, card icons, conditions treated and diagnostic services |

Requests move through a simple pipeline: **New → Contacted → Booked**, with **Cancelled**
available at any point.

### Bulk actions

Every list screen has row checkboxes and a **select-all** control in the header. A bulk
bar appears once anything is selected, offering:

| Screen | Bulk actions |
|---|---|
| Appointments | Apply a status to the selection, or delete the selection |
| Specialists | Show on website, hide from website, or remove |
| Specialties | Show on website, hide from website, or remove |

Select-all applies to **the current page only**, so a filtered or paginated view never
quietly reaches rows you cannot see. Bulk removal follows the same rules as deleting one
record: related specialists and booking requests are kept, with their foreign keys nulled.

Selection is handled client-side by the `bulkSelection` Alpine component in
`resources/js/app.js`; the selected ids are mirrored into hidden inputs inside the bulk
form, since the tables already contain per-row forms and forms cannot be nested.

### Editing profile pages

The specialist form has a repeatable **Profile page sections** editor (the
`profileSections` Alpine component). Each block has a heading, a layout — bulleted list or
paragraphs — and a content box, and can be reordered or removed. Blocks with no content are
discarded on save, so an accidentally added empty row never renders a stray heading.

Textareas treat input differently per layout: a **list** uses one line per bullet, while
**paragraphs** are separated by a blank line, so a long paragraph that wraps inside the box
is still stored as a single paragraph.

---

## How the booking form works

1. Any "Book an Appointment" button dispatches an Alpine event that opens the global modal.
2. Specialist cards pass their id and specialty into the modal, pre-filling the form.
3. `POST /appointments` is validated by `StoreAppointmentRequest`.
4. On success the request is stored with a `new` status and the modal re-opens showing a confirmation.
5. On failure the patient is redirected back: the modal re-opens with their old input and the errors listed.

Validation covers required contact details, that the specialty exists, that the chosen
specialist is currently visible on the website, and that the preferred date is not in the past.

---

## Data model

```
specialties ──< specialists ──< appointments
```

| Table | Notes |
|---|---|
| `specialties` | Name, slug, card icon, descriptions, `conditions` and `diagnostic_services` (JSON arrays), display order, visibility |
| `specialists` | Name, slug, qualifications, role, specialty FK, headshot URL, bio, consulting days, `special_interests` (JSON), `profile_sections` (JSON), display order, visibility |
| `appointments` | Patient contact details, specialty **snapshot**, optional specialist FK, referral status, preferred date/time, notes, workflow status, internal admin notes |
| `users` | Staff accounts for the admin panel |

Two deliberate choices keep history intact:

- `appointments.specialty` stores the specialty **name as text**, so a request survives a department being renamed or removed.
- Removing a specialist or specialty **nulls** the related foreign key rather than cascading, so booking history is never destroyed.

Slugs are generated automatically from the name and kept unique (`dr-same-name`, `dr-same-name-2`).

---

## Testing

```bash
php artisan test
```

81 tests / 314 assertions covering the homepage, the About page, the specialist profile
pages, the booking flow end to end, admin authentication and access control, appointment
triage, bulk actions, and specialist/specialty CRUD.

```bash
./vendor/bin/pint    # code style
```

Tests run against an in-memory SQLite database, so they never touch `database/database.sqlite`.

---

## Project layout

```
app/
  Http/Controllers/            HomeController, AboutController, SpecialistController,
                               AppointmentController
  Http/Controllers/Admin/      Auth, Dashboard, Appointments, Specialists, Specialties
  Http/Requests/               StoreAppointmentRequest (public booking validation)
  Models/                      Appointment, Specialist, Specialty (+ Concerns/HasSlug)
  View/Composers/              SiteComposer — navbar & booking modal data
config/clinic.php              Address, phone, hours, map, referral notice
config/admin.php               Admin credentials and branding
resources/views/
  layouts/app.blade.php        Public layout
  layouts/admin.blade.php      Admin layout (sidebar + topbar)
  home.blade.php               Homepage
  about.blade.php              About Us page
  specialists/show.blade.php   Individual doctor profile page
  components/                  Navbar, footer, booking modal, cards, icons
  admin/                       Dashboard, appointments, specialists, specialties
resources/css/app.css          Tailwind theme tokens and brand palette
database/seeders/              Specialties, specialists, admin user, sample requests
```

---

## Specialist headshots

Headshots live in `public/images/specialists/` and are named after the specialist's
**slug**, so the file and the database record stay easy to pair up:

```
public/images/specialists/dr-sadasivan.png
public/images/specialists/dr-sarah-tanusha-thomas.jpeg
public/images/specialists/dr-subakumar.jpg
public/images/specialists/dr-harish-venugopal.jpg
```

There is no upload UI yet — drop the file in that folder and set the path on the
specialist in **Admin → Specialists**, e.g. `/images/specialists/dr-sadasivan.png`.
A hosted URL (`https://…`) works equally well, which is what the remaining doctors
use until clinic photography is supplied. Leave the field blank to fall back to a
placeholder avatar.

> Note that anything in `public/build/` is Vite's output directory and is wiped on every
> `npm run build`. Keep uploaded images in `public/images/`, never in the build folder.

---

## Notes

- `legacy-react-prototype.tar.gz` holds the original React/Vite prototype this Laravel
  application replaced, kept as a design reference. It is safe to delete.
- The hero, clinic-exterior and remaining doctor images are still hot-linked from
  Unsplash. Replace them with clinic-owned photography before going live.
- Emails are written to the log (`MAIL_MAILER=log`). Configure a real mailer in `.env`
  if you want booking notifications delivered to reception.
