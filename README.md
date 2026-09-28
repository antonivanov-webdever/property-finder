# Property Finder — Interactive Real Estate Map

A web application for publishing and managing real estate objects on an interactive map of Moscow.
It consists of a public map with category filtering and a protected admin panel with full CRUD
management of map points, categories, and bulk data import from CSV.

<p>
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white">
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel&logoColor=white">
  <img alt="Vue" src="https://img.shields.io/badge/Vue.js-3-4FC08D?logo=vuedotjs&logoColor=white">
  <img alt="Inertia" src="https://img.shields.io/badge/Inertia.js-1.x-9553E9">
  <img alt="Tailwind" src="https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?logo=tailwindcss&logoColor=white">
  <img alt="MySQL" src="https://img.shields.io/badge/MySQL-8-4479A1?logo=mysql&logoColor=white">
  <img alt="Docker" src="https://img.shields.io/badge/Docker-compose-2496ED?logo=docker&logoColor=white">
  <img alt="License" src="https://img.shields.io/badge/License-MIT-green">
</p>

---

<!-- SCREENSHOTS: put images into docs/screenshots/ and uncomment the block below.
     This is the most-viewed part of a portfolio README — 2-4 images are worth adding.

| Public map | Admin panel |
|---|---|
| ![Public map](docs/screenshots/map.png) | ![Points list](docs/screenshots/admin-points.png) |
| Category filter and object balloon | Search, filters, bulk publishing |

-->

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Architecture](#architecture)
- [Data Model](#data-model)
- [Routes](#routes)
- [Running Locally](#running-locally)
- [Running with Docker](#running-with-docker)
- [Environment Variables](#environment-variables)
- [Tests](#tests)
- [Project Structure](#project-structure)
- [Implementation Notes](#implementation-notes)
- [License](#license)

---

## Features

### Public side

- Interactive map built on Yandex Maps JavaScript API 2.1.
- Points are served as a single GeoJSON-like `FeatureCollection` and rendered through
  `ymaps.ObjectManager`, which keeps a large number of objects on the map without
  degrading performance.
- Category filter: checkboxes are built dynamically from the category reference table,
  and filtering runs client-side through `objectManager.setFilter()` — no page reload
  and no extra request to the server.
- Custom balloons: object photo, address, structured description, and links to a Telegram
  discussion thread and a YouTube video tour.
- Every category carries its own marker icon.
- Only points flagged as `is_visible` appear on the public map, so a content manager can
  prepare objects first and publish them as a separate action.

### Admin panel

- Authentication via Laravel Jetstream (Fortify + Sanctum): login, two-factor authentication,
  email verification, browser session management, and profile settings.
- Point CRUD: create, edit, delete, and photo upload with type/size validation and a
  placeholder image substituted when no photo is provided.
- Coordinates are picked by clicking the map: a marker is placed at the click position and
  the address (city, district, street, house) is filled in automatically by reverse geocoding
  through `ymaps.geocode`.
- Category CRUD with marker icon upload.
- Point search by name and ID, filters by category, photo presence, and publication status,
  with pagination at 15 records per page.
- Bulk publish/unpublish straight from the table.
- CSV import: the parser locates the latitude, longitude, and description columns from the
  header row, splits the description into name / developer / address, and creates the points
  in a hidden state under a service category.
- Public map preview inside the admin panel — the same map component, but showing every point
  including unpublished ones.

---

## Tech Stack

| Layer | Technologies |
|---|---|
| Backend | PHP 8.2, Laravel 11, Eloquent ORM |
| Auth | Laravel Jetstream 5 (Fortify, Sanctum), 2FA |
| Frontend | Vue 3 (Composition API, `<script setup>`), Inertia.js 1.x, Tailwind CSS 3 |
| Build | Vite 5, SSR build, Ziggy (Laravel routes in JS) |
| Maps | Yandex Maps JavaScript API 2.1 (`ObjectManager`, geocoder) |
| Database | MySQL 8 |
| Testing | Pest 3 |
| Infrastructure | Docker, Docker Compose, Nginx, PHP-FPM |
| Code style | Laravel Pint |

---

## Architecture

The application is built as an **Inertia.js monolith**: Laravel remains the single source of
routing and data, while Vue components are rendered as pages — without a separate SPA router
and without a hand-written REST layer for the admin panel.

```
Browser
  │
  ├── Public map (Inertia page: Home)
  │     └── GET /getPointsOMJson  → FeatureCollection (JSON)
  │     └── GET /getAll           → category reference data
  │
  └── Admin panel (Inertia pages: Admin/*)
        └── auth:sanctum + jetstream session + verified + IpMiddleware
              └── PointController / CategoryController / AdminController
                    └── Eloquent (MySQL) + `admin` filesystem disk
```

There are two Blade entry points — `app.blade.php` for the public side and `admin.blade.php`
for the admin panel. Splitting them keeps the Yandex Maps script and the analytics counter
loaded only where they are actually needed.

---

## Data Model

```
categories                        points                              ip_addresses
──────────                        ──────                              ────────────
id                                id                                  id
name                              image                               ip_address
icon                              name                                timestamps
timestamps                        address
                                  description
     │                            youtube_link  (nullable)
     └──────< category_id ────────  tg_link     (nullable)
        (cascade on delete)        coordinates  (JSON → array cast)
                                   is_visible   (bool, default false)
                                   timestamps
```

`coordinates` is stored as a JSON array `[latitude, longitude]` and converted to a PHP array
by the Eloquent cast `'coordinates' => 'array'`, so serialization lives entirely in the model
while controllers and Vue components work with a plain array.

---

## Routes

### Public

| Method | URI | Purpose |
|---|---|---|
| `GET` | `/` | Public page with the map |
| `GET` | `/getPointsOMJson` | Points as a `FeatureCollection` for `ObjectManager` |
| `GET` | `/getAll` | Category reference data for the filter |

### Admin panel (`/admin`, middleware `auth:sanctum` + `verified`)

| Method | URI | Purpose |
|---|---|---|
| `GET` | `/admin/dashboard` | Map preview |
| `POST` | `/admin/csv` | CSV point import |
| `GET` | `/admin/points` | Paginated point list |
| `GET` | `/admin/points/create` | Point creation form |
| `POST` | `/admin/points/create` | Create a point |
| `GET` | `/admin/points/{point}/edit` | Point edit form |
| `POST` | `/admin/points/{point}` | Update a point |
| `DELETE` | `/admin/points/{point}` | Delete a point |
| `POST` | `/admin/points/save` | Bulk visibility update |
| `POST` | `/admin/points/filter` | Filter the list |
| `GET` | `/admin/points/search` | Search by name and ID |
| `GET/POST/DELETE` | `/admin/categories/…` | Category CRUD |

---

## Running Locally

**Requirements:** PHP 8.2+, Composer, Node.js 18+, MySQL 8.

```bash
git clone https://github.com/antonivanov-webdever/property-finder.git
cd property-finder

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Set the database credentials and the Yandex Maps key in `.env`
(see [Environment Variables](#environment-variables)), then run:

```bash
php artisan migrate
php artisan storage:link
npm run build          # or npm run dev for development mode
php artisan serve
```

The application is served at `http://localhost:8000`.
Register the first user at `/register` to get access to `/admin/dashboard`.

A combined command is available for development — it starts the server, queue worker, log
viewer, and Vite at once:

```bash
composer run dev
```

---

## Running with Docker

The repository ships a `Dockerfile` (PHP 8.2-FPM with the `gd`, `zip`, and `pdo_mysql`
extensions) and a `docker-compose.yml` with two services: `app` (PHP-FPM) and `webserver`
(Nginx, configured in `docker/nginx/default.conf`, with Let's Encrypt TLS certificate support).

```bash
docker compose up -d --build
docker compose exec app php artisan migrate
```

> The compose file expects an external database — connection parameters are read from `.env`.
> For a fully self-contained environment, add a `mysql` service to `docker-compose.yml`.

---

## Environment Variables

In addition to the standard Laravel variables, the project needs:

| Variable | Purpose |
|---|---|
| `MAPS_API_KEY` | Yandex Maps JavaScript API key (read in `config/services.php` → `services.maps.api_key`) |
| `DB_*` | MySQL connection (`DB_DATABASE=property_finder` by default) |
| `APP_URL` | Base URL — links to files on the `admin` disk are built from it |

Uploaded images are written to a dedicated `admin` filesystem disk (`storage/app/admin`,
exposed as `/admin`), configured in `config/filesystems.php`.

---

## Tests

```bash
php artisan test
# or
./vendor/bin/pest
```

The current suite consists of the Jetstream-provided feature tests for authentication,
profile management, 2FA, and API tokens, running on Pest 3. The domain logic for points and
categories is not covered yet — that is the next item on the project roadmap.

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── SiteController.php       public page
│   │   ├── PointController.php      CRUD, search, filters, map JSON
│   │   ├── CategoryController.php   category CRUD
│   │   └── AdminController.php      dashboard, CSV import, filtering
│   └── Middleware/
│       ├── HandleInertiaRequests.php
│       └── IpMiddleware.php         IP-based access restriction
├── Models/
│   ├── Point.php                    + FeatureCollection builder
│   ├── Category.php
│   └── IpAddress.php
└── Actions/                         Fortify / Jetstream

resources/js/
├── Pages/
│   ├── Home.vue                     public map
│   ├── Admin/                       lists, forms, preview
│   └── Shared/Partials/
│       ├── Map.vue                  map, ObjectManager, balloons
│       ├── CategoryFilter.vue       category filter
│       └── baloonHtml.js            balloon templates
├── Components/                      reusable UI (uploaders, modals, pagination)
├── Layouts/AppLayout.vue            admin panel shell
└── composables/useYandexMaps.js     lazy Yandex Maps API loader

database/migrations/                 database schema
docker/nginx/                        Nginx configuration
```

---

## Implementation Notes

**`ObjectManager` instead of individual placemarks.** Points are delivered in a single request
as a `FeatureCollection` and rendered by Yandex Maps itself. This keeps DOM pressure low as the
number of objects grows, and category filtering reduces to one `setFilter()` call instead of
recreating markers.

**Lazy map loading.** The `useYandexMaps` composable injects the API script once and only on
pages that actually render a map, handling repeated component mounts correctly.

**Eloquent cast for coordinates.** Coordinates are stored as JSON and converted to an array by
a model cast, so neither the controllers nor the frontend deal with manual `json_encode` /
`JSON.parse`.

**Dedicated filesystem disk for uploads.** Point images and category icons are written to the
`admin` disk (`storage/app/admin`), isolated from the public `storage` disk so upload paths
never collide with application assets.

**IP-based access restriction.** `IpMiddleware` checks the client IP against the `ip_addresses`
table (via `Symfony\IpUtils`, with range support) and lets only the public routes through
unchecked — an extra barrier in front of the admin panel on top of authentication.

**One map component for both the storefront and the admin panel.** `Map.vue` powers both the
public page and the admin preview; the mode is derived from the request origin and determines
whether unpublished points are shown.

**SSR build.** Vite is configured for a two-pass build (`vite build && vite build --ssr`), with
Inertia server-side rendering wired up through `resources/js/ssr.js`.

---

## License

Released under the [MIT License](LICENSE).

---

## Author

**Anton Ivanov** — [github.com/antonivanov-webdever](https://github.com/antonivanov-webdever)
