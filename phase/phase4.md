# NontonKu Phase 4 — Public Content Catalog & Detail

## Objective

Implement Phase 4 of NontonKu by connecting the existing Phase 2 database models with the Phase 3 public Blade UI.

This phase introduces the first real public content browsing functionality.

The application must remain Laravel Blade + Tailwind CSS + Alpine.js.

Do not redesign the existing Phase 3 visual system.

---

## Scope

Implement:

1. Dynamic homepage content sections
2. Public catalog pages
3. Content detail pages
4. Movie detail handling
5. Series/Anime/Donghua season and episode handling
6. Genre filtering
7. Basic search
8. Sorting
9. Pagination
10. Published-content filtering
11. Slug-based public URLs
12. SEO metadata based on content
13. Content JSON-LD structured data
14. Basic view counting infrastructure
15. Reusable Content Card component
16. Automated tests

---

## Important Architecture Rules

Follow the existing architecture:

Routes
→ Controllers
→ Form Requests where needed
→ Services / Query logic
→ Models
→ Blade Views

Controllers must remain thin.

Do not place database queries inside Blade.

Do not introduce a heavy repository abstraction unless it is actually necessary.

Use Eloquent relationships and scopes appropriately.

---

## Content Visibility

Public users must ONLY see:

contents.is_published = true

and:

published_at IS NULL
OR
published_at <= now()

The same principle must apply to episodes.

Unpublished content and episodes must never appear on public pages.

---

## Routes

Create named routes:

GET /
GET /movies
GET /series
GET /anime
GET /donghua

GET /movies/{content:slug}
GET /series/{content:slug}
GET /anime/{content:slug}
GET /donghua/{content:slug}

GET /search

Use route model binding where appropriate.

Do not use numeric content IDs in public URLs.

---

## Homepage

Replace Phase 3 placeholder content with database-driven sections.

Suggested sections:

- Featured
- Popular
- Latest Movies
- Latest Series
- Latest Anime
- Latest Donghua

Only display sections that contain content.

Do not create unnecessary queries.

Use eager loading to avoid N+1 problems.

---

## Catalog

Each catalog page must support:

- pagination
- genre filtering
- sorting
- basic search within the catalog if appropriate

Sorting options:

- latest
- oldest
- rating
- popular
- title

Validate query parameters.

Do not trust arbitrary column names from the request.

Whitelist allowed sort values.

---

## Search

Implement:

GET /search?q=

Search:

- title
- original_title

Search must be:

- validated
- length limited
- escaped safely through Laravel/Eloquent
- paginated

Do not implement full-text search engines yet.

---

## Content Detail

Movie:

- poster
- backdrop
- title
- original title
- description
- genres
- release date
- duration
- age rating
- status
- rating
- views
- video source availability indicator
- download source availability indicator

Series/Anime/Donghua:

Everything above plus:

- seasons
- episodes
- episode number
- episode title
- episode duration
- episode release date
- episode thumbnail
- published status

Never expose inactive sources publicly.

---

## Content Card

Create or refactor:

<x-content-card>

It should support:

- poster
- title
- year
- type
- rating
- age rating
- status
- link to detail page

It must work with the existing Phase 3 light/dark design system.

Do not duplicate card markup across pages.

---

## Slug URLs

Use:

/movies/{slug}
/series/{slug}
/anime/{slug}
/donghua/{slug}

Do not expose:

/content/{id}

If a content type does not match the route, return 404.

For example:

/movies/some-anime

must return 404 if that content has type anime.

---

## SEO

Use the existing public layout SEO slots.

Content detail pages must dynamically provide:

- title
- meta description
- canonical URL
- Open Graph title
- Open Graph description
- Open Graph image when available
- Open Graph URL

Do not invent fake production URLs.

Use config/app URL where appropriate.

---

## JSON-LD

Generate structured data according to content type.

Movie:

Movie

Series:

TVSeries

Anime:

TVSeries

Donghua:

TVSeries

Use safe JSON encoding.

Do not place arbitrary user-generated HTML into JSON-LD.

---

## View Counting

Create:

App\Services\ContentViewService

Do not simply increment views directly inside the controller.

For Phase 4, a basic implementation is sufficient.

The service should make it easy to upgrade later to:

- unique views
- daily views
- weekly views
- monthly views

Do not over-engineer this phase.

---

## Performance

Avoid N+1 queries.

Use appropriate eager loading.

For catalog pages, do not eager-load large unnecessary relationships.

Use:

with()
withCount()

where appropriate.

Do not load all contents into memory.

Use pagination.

---

## Security

Validate all query parameters.

Whitelist:

- sort
- genre
- page
- search

Do not allow arbitrary SQL columns through request parameters.

Do not expose unpublished content.

Do not expose inactive media sources.

---

## Authentication

Authentication is NOT required for browsing.

Guests can:

- browse
- search
- view content details
- view episodes

Authenticated user features such as Favorite and Watchlist will be implemented later.

Do not implement those features in Phase 4.

---

## UI

Preserve Phase 3 design.

Do not redesign the site.

Maintain:

- white dominant light mode
- dark mode
- mobile-first
- clean cards
- minimal shadows
- minimal animation
- accessible focus states
- reduced-motion support

---

## Empty States

Every catalog/search page must have a proper empty state.

Examples:

"No movies found."

"No anime found."

"No results for your search."

Use the existing:

<x-empty-state>

component.

---

## Testing

Create tests for:

1. Homepage returns published content
2. Unpublished content is hidden
3. Future published content is hidden
4. Movie catalog works
5. Series catalog works
6. Anime catalog works
7. Donghua catalog works
8. Content detail works
9. Invalid slug returns 404
10. Wrong content type returns 404
11. Episodes are displayed correctly
12. Unpublished episodes are hidden
13. Pagination works
14. Genre filtering works
15. Sorting works
16. Search works
17. Search validation works
18. SEO metadata exists
19. JSON-LD exists
20. N+1-sensitive queries are reasonably optimized
21. View count service works

---

## Regression

Existing Phase 1–3 tests MUST continue passing.

Never delete or weaken an existing test.

Run:

php artisan test

Also run:

php artisan migrate:fresh --seed

before final verification.

---

## Completion Criteria

Phase 4 is complete only when:

- Public homepage uses real database content
- Catalog pages use real database content
- Content detail pages work
- Episodes work
- Search works
- Filters work
- Pagination works
- SEO works
- JSON-LD works
- Unpublished content remains private
- Existing authentication continues working
- Existing Phase 1–3 tests remain passing
- No N+1 problems are introduced unnecessarily

STOP after Phase 4.

Do not implement Phase 5 automatically.
