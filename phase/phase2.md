# NontonKu — Phase 2: Database & Eloquent Models

Phase 0 and Phase 1 are complete and verified.

Phase 1 verification:

- 28 tests passed
- 66 assertions passed
- Authentication works
- Admin authorization works
- Normal users are blocked from admin routes

Before starting, read and follow:

- docs/PROJECT.md
- docs/ARCHITECTURE.md
- docs/DATABASE.md
- docs/UI.md
- docs/STREAMING.md
- docs/SECURITY.md
- docs/SEO.md
- docs/ROADMAP.md
- AGENTS.md

The database design in docs/DATABASE.md is FINAL.

Do not redesign the database.

---

# Objective

Implement the finalized NontonKu database schema and Eloquent model foundation.

This phase must establish:

- Database migrations
- PHP Enums
- Eloquent models
- Relationships
- Attribute casts
- Factories
- Seeders
- Policies where appropriate
- Database tests

Do not implement the public catalog or Admin CMS yet.

---

# Database

Use MySQL.

Implement the database exactly according to:

docs/DATABASE.md

The finalized tables are:

1. users
2. contents
3. genres
4. content_genre
5. seasons
6. episodes
7. video_sources
8. download_sources
9. subtitles
10. favorites
11. watchlists
12. watch_histories
13. episode_bookmarks
14. ratings
15. comments
16. reports
17. banners
18. settings

Do not add unrelated tables.

Do not remove finalized tables.

---

# Important Database Rules

## Content Type

Do NOT use a database ENUM.

Use:

string `type`

with a PHP backed enum:

ContentType

Values:

- movie
- series
- anime
- donghua

The database remains flexible while the application uses type-safe enums.

---

# Content Status

Create a PHP backed enum:

ContentStatus

Initial values should include:

- ongoing
- completed
- upcoming
- hiatus
- cancelled

Do not use database ENUM.

Keep the implementation easy to extend.

---

# User Role

Keep:

users.role

as a string.

Initial values:

- user
- admin
- editor
- moderator

Do not install Spatie Permission.

Do not create roles or permissions tables.

The architecture should remain migration-friendly for future granular authorization.

---

# Models

Create Eloquent models for all finalized database entities.

Expected models include:

User
Content
Genre
Season
Episode
VideoSource
DownloadSource
Subtitle
Favorite
Watchlist
WatchHistory
EpisodeBookmark
Rating
Comment
Report
Banner
Setting

Use singular Laravel model names.

---

# Content Relationships

Content should have:

- belongsToMany Genre
- hasMany Season
- morphMany VideoSource
- morphMany DownloadSource
- morphMany Subtitle
- hasMany Favorite
- hasMany Watchlist
- hasMany WatchHistory
- hasMany Rating
- hasMany Comment
- hasMany Banner

Use appropriate relationship names.

---

# Season Relationships

Season:

- belongsTo Content
- hasMany Episode

Use:

content_id

as the foreign key.

---

# Episode Relationships

Episode:

- belongsTo Season
- hasMany VideoSource through morphMany
- hasMany DownloadSource through morphMany
- hasMany Subtitle through morphMany
- hasMany WatchHistory
- hasMany EpisodeBookmark
- hasMany Comment

Episode should be able to access its parent Content through:

Season → Content

If useful, provide a convenient relationship/accessor without duplicating foreign keys.

---

# User Relationships

User should have:

- hasMany Favorite
- hasMany Watchlist
- hasMany WatchHistory
- hasMany EpisodeBookmark
- hasMany Rating
- hasMany Comment
- hasMany Report

Do not create unnecessary relationships.

---

# Polymorphic Relationships

Use Laravel polymorphic relationships for:

VideoSource
DownloadSource
Subtitle
Report

VideoSource:

morphTo sourceable

DownloadSource:

morphTo sourceable

Subtitle:

morphTo sourceable

Report:

morphTo reportable

Use morphMany on Content and Episode where appropriate.

Do not replace these relationships with separate duplicate foreign-key systems.

---

# Watch History

Important:

watch_histories contains:

- user_id
- content_id
- episode_id nullable

Do NOT add a database unique constraint involving nullable episode_id.

The intended uniqueness is enforced by application logic:

Movie:

user + content

Episode:

user + episode

Create a dedicated service:

WatchHistoryService

responsible for:

- finding existing history
- creating history
- updating progress
- updating duration
- marking completed
- updating last_watched_at

Do not put this logic in controllers.

Keep the service small and focused.

---

# Ratings

Ratings use:

rating 1–10

Database constraint:

unique(user_id, content_id)

Content fields:

average_rating
ratings_count

are cached/denormalized values.

Do not treat them as the source of truth.

The actual source of truth is:

ratings

Do not build the full rating UI yet.

---

# Database Indexes

Implement all indexes and unique constraints defined in DATABASE.md.

Pay particular attention to:

contents:
(type, is_published)

seasons:
(content_id, season_number) UNIQUE

episodes:
(season_id, episode_number) UNIQUE
(season_id, is_published, episode_number)

video_sources:
(sourceable_type, sourceable_id)

download_sources:
(sourceable_type, sourceable_id)

subtitles:
(sourceable_type, sourceable_id)

favorites:
(user_id, content_id) UNIQUE

watchlists:
(user_id, content_id) UNIQUE

watch_histories:
(user_id, content_id)
(user_id, episode_id)

episode_bookmarks:
(user_id, episode_id) UNIQUE

ratings:
(user_id, content_id) UNIQUE

comments:
(content_id, is_approved)
(episode_id, is_approved)

reports:
(reportable_type, reportable_id)

users:
role

Do not add excessive indexes that are not justified.

---

# Foreign Key Behavior

Follow DATABASE.md.

Use cascading deletes for dependent user/content records where defined.

Important:

reports.user_id

must use:

nullable
nullOnDelete

so historical reports remain when the user is deleted.

---

# Casts

Use Laravel casts appropriately.

Examples:

Content:

type → ContentType enum
status → ContentStatus enum
average_rating → decimal handling
is_featured → boolean
is_published → boolean
published_at → datetime

Episode:

is_published → boolean
published_at → datetime

VideoSource:

is_active → boolean

DownloadSource:

is_active → boolean

Comments:

is_approved → boolean

Reports:

status can later become a PHP enum, but do not over-engineer unless necessary.

---

# Mass Assignment

Use explicit `$fillable` or guarded strategy.

Do not expose sensitive fields such as:

- password
- remember_token

through unsafe mass assignment.

Follow Laravel security conventions.

---

# Settings Model

The settings table uses:

key
value
type

The model should provide a clean way to retrieve the value.

Do not build a complex configuration framework.

A small helper/service is acceptable if useful.

---

# Factories

Create factories for important models.

At minimum:

User
Content
Genre
Season
Episode
VideoSource
DownloadSource
Subtitle
Favorite
Watchlist
WatchHistory
EpisodeBookmark
Rating

Future-only entities such as comments/reports may also have factories if useful.

Factories must generate realistic but deterministic-enough test data.

Do not create external URLs that look like real provider integrations.

Use placeholder/example URLs for testing only.

---

# Seeders

Create a development DatabaseSeeder.

Seed a small realistic dataset:

Users:

- admin user
- normal user

Content:

- at least one movie
- at least one series
- at least one anime
- at least one donghua

Genres:

several realistic genres.

For series/anime/donghua:

- seasons
- episodes

Also include a few example:

- video sources
- download sources
- subtitles

Use clearly fake/example URLs.

Do not use copyrighted real streaming/download links.

The seed data exists only to verify relationships and development UI later.

---

# Admin User

Create a development admin user through the seeder.

Do not hardcode a production password into source code.

Use environment/configurable values or a clearly documented local development credential mechanism.

Never expose real secrets.

---

# Policies

Create only policies that are actually useful at this phase.

At minimum prepare authorization for user-owned resources where appropriate.

Do not build a complete Admin CMS authorization system yet.

Admin authorization continues to use the existing Phase 1 middleware/gate.

---

# Tests

Create database/model tests.

At minimum test:

## Content

- Content can be created.
- Content type is correctly cast to ContentType.
- Content status is correctly cast to ContentStatus.
- Content can have genres.
- Content can have seasons.
- Movie can have video sources.
- Episode can have video sources.

## Relationships

Test:

Content → Genres
Content → Seasons
Season → Episodes
Episode → Season
Content → VideoSources
Episode → VideoSources
Content → DownloadSources
Episode → DownloadSources
Content → Subtitles
Episode → Subtitles

## User Features

Test:

User → Favorite
User → Watchlist
User → WatchHistory
User → EpisodeBookmark
User → Rating

Verify unique constraints:

- duplicate favorite prevented
- duplicate watchlist prevented
- duplicate bookmark prevented
- duplicate rating prevented

## Watch History

Test:

Movie history is unique logically by:

user + content

Episode history is unique logically by:

user + episode

Test that WatchHistoryService updates an existing history instead of creating duplicates.

Test progress update.

Test completed state.

Test last_watched_at update.

## Ratings

Test:

- rating must be between 1 and 10
- duplicate user/content rating prevented
- average_rating and ratings_count are treated as cached values

Do not implement automatic rating aggregation unless it is explicitly required by the architecture.

## Cascade / Delete Behavior

Test important cascade relationships:

Deleting Content should appropriately delete:

- seasons
- episodes
- favorites
- watchlists
- relevant histories
- etc.

Deleting a User should appropriately delete personal records.

Deleting a User must NOT delete the report itself.

The report's user_id should become NULL.

## Polymorphic Relations

Test:

Content → VideoSource
Episode → VideoSource
Content → DownloadSource
Episode → DownloadSource
Content → Subtitle
Episode → Subtitle

---

# Migration Order

Ensure migrations run successfully from a fresh database.

Respect foreign-key dependencies.

The migration sequence must not require manually disabling foreign-key checks.

---

# Code Quality

Follow:

- Laravel conventions
- PSR-12
- thin models where appropriate
- clear relationships
- no business logic in migrations
- no business logic in Blade
- no database queries in Blade
- no unnecessary repositories
- no unnecessary abstractions

Do not create a Repository Layer unless there is a demonstrated need.

The architecture should remain:

Controller
→ Form Request
→ Service
→ Model
→ Database

where business logic is required.

---

# Important

Do NOT implement:

- Homepage
- Catalog
- Search UI
- Content detail page
- Admin content CRUD
- Streaming player
- Download UI
- Watchlist UI
- Favorite UI
- Rating UI
- Comment UI
- Report UI
- SEO pages
- Sitemap
- PWA

Those belong to later phases.

---

# Verification

Run:

php artisan migrate:fresh --seed

Then:

php artisan test

Also verify:

php artisan migrate:status

All migrations must succeed from a fresh database.

---

# Completion Report

When finished, report:

1. Migrations created
2. Models created
3. Enums created
4. Services created
5. Policies created
6. Factories created
7. Seeders created
8. Relationships implemented
9. Indexes/constraints implemented
10. Tests created
11. Test result
12. Migration result
13. Seed result
14. Any warnings/errors
15. Files changed

Do NOT start Phase 3 automatically.

Stop after Phase 2 is complete.
