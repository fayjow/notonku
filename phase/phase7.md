# PHASE 7 IMPLEMENTATION REQUEST — NontonKu

## Admin Content Management & Streaming Source Management

You are continuing development of the existing Laravel project:

Project Name: NontonKu

IMPORTANT:
This is NOT a new project.
You are working on the existing NontonKu codebase.

The project has completed Phase 1–6 successfully.

Current verification status:

php artisan test

Result:
86 tests passed

You MUST preserve all existing functionality and architecture from Phase 1–6.

============================================================

# CORE RULE — DO NOT BREAK EXISTING FEATURES

============================================================

Before making any modification:

1. Inspect the existing codebase.
2. Understand the current:
    - Models
    - Relationships
    - Migrations
    - Controllers
    - Policies
    - Middleware
    - Routes
    - Blade components
    - Services
    - Tests
    - Authentication
    - Admin authorization
    - Dark/light mode
    - Tailwind/Alpine.js implementation

DO NOT blindly overwrite existing files.

Reuse existing architecture whenever possible.

DO NOT duplicate functionality that already exists.

DO NOT create a second implementation of an existing service or relationship.

The existing 86 tests MUST continue to pass after Phase 7.

At the end of implementation:

php artisan test

MUST report:

Tests: [new total] passed

There must be ZERO failed tests.

============================================================

# CURRENT PROJECT STATUS

============================================================

Phase 1–4:

- Authentication
- Registration
- Login/logout
- Email verification
- Password reset
- Profile
- Admin authorization
- Content catalog
- Search
- Pagination
- Sorting
- Published-content visibility
- Genres
- Seasons
- Episodes
- Database relationships

Phase 5:

- Favorites
- Episode bookmarks / Watchlist
- Watch History
- Continue Watching
- Ratings
- User feature pages
- Alpine.js AJAX interactions

Phase 6:

- HTML5 video player
- WatchController
- Explicit watch routes
- Movie playback
- Series playback
- Anime playback
- Donghua playback
- Resume playback
- Watch progress synchronization
- Next/Previous episode
- Auto-next episode
- Episode sidebar
- Published-content authorization
- Episode/content relationship validation
- Video source handling
- Dark/light mode

Current test count:
86 tests passed

============================================================

# PHASE 7 GOAL

============================================================

Implement:

ADMIN CONTENT MANAGEMENT &
STREAMING SOURCE MANAGEMENT

The administrator should be able to manage the entire NontonKu catalog from the Admin Panel without manually modifying database records.

Phase 7 should provide a professional admin experience for:

1. Dashboard
2. Content management
3. Genre management
4. Season management
5. Episode management
6. Video source management
7. Publishing management
8. Search/filter/sort
9. Validation
10. Authorization
11. Automated testing

============================================================

# 1. ADMIN DASHBOARD

============================================================

Create/improve the admin dashboard.

Suggested route:

/admin

Use the existing admin authorization architecture.

Dashboard should display useful statistics such as:

- Total content
- Published content
- Draft/unpublished content
- Movies
- Series
- Anime
- Donghua
- Total seasons
- Total episodes
- Total users
- Total ratings
- Total favorites
- Total watch history records
- Total video sources

Use efficient database queries.

Avoid unnecessary N+1 queries.

Use aggregate queries/counts where appropriate.

Dashboard UI:

- Clean
- Modern
- Responsive
- Tailwind CSS
- Existing NontonKu design language
- Existing dark/light mode
- Cards/statistics
- Simple icons
- Clear typography
- Mobile friendly

Do NOT introduce a heavy UI framework unless the project already uses one.

============================================================

# 2. ADMIN CONTENT MANAGEMENT

============================================================

Create a complete CRUD interface for Content.

Possible routes:

GET /admin/content
GET /admin/content/create
POST /admin/content
GET /admin/content/{content}/edit
PUT /admin/content/{content}
DELETE /admin/content/{content}

Use route naming consistent with the existing project.

Content fields should be based on the EXISTING Content model and migration.

DO NOT invent duplicate columns if they already exist.

First inspect the existing schema.

The admin should be able to manage:

- Title
- Slug
- Type
- Description
- Poster
- Backdrop
- Release year
- Status
- Published state
- Genres
- Other existing metadata

Supported content types:

- movie
- series
- anime
- donghua

Use the project's existing enum/value conventions.

IMPORTANT:

Do not create a new content-type system if one already exists.

Reuse existing enums/casts.

============================================================

# 3. CONTENT LIST PAGE

============================================================

Create a professional content management table.

Features:

- Search by title
- Filter by type
- Filter by published/unpublished
- Sort by title
- Sort by release year
- Sort by created date
- Pagination
- Edit
- Delete
- Publish/unpublish

Example:

---

## CONTENT MANAGEMENT

Search: [________________]

Type:
[All] [Movie] [Series] [Anime] [Donghua]

Status:
[All] [Published] [Draft]

---

## Poster | Title | Type | Year | Status | Actions

Use pagination.

Do not load the entire database into memory.

============================================================

# 4. CREATE / EDIT CONTENT

============================================================

Create a reusable form component if appropriate.

The form must support:

- Validation
- Old input
- Validation error display
- Proper labels
- Accessible inputs
- Dark mode
- Responsive layout

Validation should use Laravel Form Request classes where appropriate.

For example:

StoreContentRequest
UpdateContentRequest

But:

If the project already has an established validation architecture, follow that instead.

============================================================

# 5. PUBLISH / UNPUBLISH

============================================================

Admins must be able to:

- Publish content
- Unpublish content

Use secure POST/PATCH actions.

Do not allow arbitrary user input to modify the published state.

Published visibility must remain compatible with Phase 4 and Phase 6.

IMPORTANT:

Unpublished content must NOT suddenly become publicly watchable.

Existing WatchController authorization rules must remain intact.

============================================================

# 6. GENRE MANAGEMENT

============================================================

Inspect the existing Genre model and migration.

If Genre CRUD does not already exist, create admin management for genres.

Features:

- List genres
- Create genre
- Edit genre
- Delete genre
- Search genre
- Show content count per genre

Prevent invalid deletion if the current relationship architecture requires protection.

Do not break the existing content_genre relationship.

============================================================

# 7. SEASON MANAGEMENT

============================================================

For Series/Anime/Donghua:

Admin must be able to manage seasons.

Example:

Content:
One Piece

Season 1
Season 2
Season 3

Features:

- Create season
- Edit season
- Delete season
- Set season number
- Set season title if supported
- View episode count

Use the existing Season model and relationships.

DO NOT create duplicate relationships.

============================================================

# 8. EPISODE MANAGEMENT

============================================================

Admin must be able to manage episodes.

Features:

- Create episode
- Edit episode
- Delete episode
- Episode number
- Episode title
- Description if supported
- Thumbnail if supported
- Duration if supported
- Published/unpublished
- Season assignment
- Video source management

Example:

Season 1

Episode 1 — Pilot
Episode 2 — The Beginning
Episode 3 — New Journey

Provide:

- Search
- Sort by episode number
- Publish/unpublish
- Edit
- Delete

Maintain correct episode ordering.

============================================================

# 9. VIDEO SOURCE MANAGEMENT

============================================================

This is an important part of Phase 7.

Inspect the existing VideoSource model, migration, polymorphic relationship, and Phase 6 WatchController implementation BEFORE changing anything.

Do not replace the existing video source architecture.

Admin should be able to manage video sources for:

- Movies
- Episodes

Possible fields depending on existing schema:

- Source name
- URL
- Quality
- Resolution
- Priority
- Active/inactive

Example:

VIDEO SOURCES

---

## Source Quality Priority Status Actions

Server 1 1080p 1 Active
Server 2 720p 2 Active
Backup 480p 3 Inactive

---

The player in Phase 6 must continue working with these sources.

============================================================

# 10. VIDEO SOURCE SECURITY

============================================================

Validate video source URLs.

Do not blindly accept arbitrary dangerous values.

Follow the existing application requirements.

Prevent unauthorized users from modifying video sources.

Only admins can create/update/delete sources.

Do not expose admin endpoints to normal users.

============================================================

# 11. ADMIN AUTHORIZATION

============================================================

Every admin management route MUST use the existing admin authorization mechanism.

Reuse the existing:

- middleware
- gate
- policy
- role system

Do NOT create a second admin authentication system.

Expected behavior:

Guest:
-> redirect/login

Authenticated normal user:
-> 403 Forbidden

Admin:
-> allowed

Existing AdminRouteTest MUST continue to pass.

============================================================

# 12. DELETE PROTECTION

============================================================

Be careful with deletion.

Before implementing delete operations, inspect:

- foreign keys
- cascade behavior
- nullable relationships
- existing CascadeDeleteTest

Examples:

Deleting content may affect:

- seasons
- episodes
- video sources
- favorites
- ratings
- watch history
- genres

DO NOT accidentally break user history or existing referential integrity.

Follow the project's existing migration behavior.

If appropriate, use confirmation dialogs in the UI.

============================================================

# 13. ADMIN NAVIGATION

============================================================

Create/improve admin navigation.

Suggested structure:

ADMIN

Dashboard

Catalog
├── Content
├── Genres
├── Seasons
└── Episodes

Streaming
└── Video Sources

Users
└── Users

Analytics
├── Ratings
└── Watch History

Only show admin navigation to administrators.

Do not expose admin navigation to normal users.

============================================================

# 14. ADMIN UI/UX

============================================================

The Admin Panel should feel like a real production application.

Requirements:

- Tailwind CSS
- Responsive
- Clean spacing
- Good typography
- Consistent buttons
- Tables
- Cards
- Forms
- Empty states
- Loading states where appropriate
- Error states
- Confirmation before destructive actions
- Dark mode
- Light mode

Reuse existing components wherever possible.

Do not unnecessarily redesign the public website.

Do not break the current public layout.

============================================================

# 15. DARK / LIGHT MODE

============================================================

Phase 4–6 already have working dark/light mode.

Phase 7 MUST support the same theme system.

Admin pages must respond correctly to:

- Light mode
- Dark mode

Do NOT implement a separate theme system.

Reuse the existing theme toggle/state mechanism.

============================================================

# 16. SEARCH / FILTER / PAGINATION

============================================================

Admin tables must be scalable.

Do NOT:

- fetch all records
- filter everything in PHP
- paginate collections manually

Use Laravel query builders/Eloquent:

where()
when()
orderBy()
paginate()

Search should be performed at database level.

============================================================

# 17. DATABASE EFFICIENCY

============================================================

Avoid N+1 queries.

Use appropriate eager loading:

with()

Examples:

Content list:

with('genres')

Episode list:

with('season.content')

Video source list:

with('sourceable')

Only load relationships actually required by the page.

Use select() when appropriate.

Do not over-eager-load unnecessary relationships.

============================================================

# 18. FORM REQUEST VALIDATION

============================================================

Where appropriate, create:

StoreContentRequest
UpdateContentRequest
StoreGenreRequest
UpdateGenreRequest
StoreSeasonRequest
UpdateSeasonRequest
StoreEpisodeRequest
UpdateEpisodeRequest
StoreVideoSourceRequest
UpdateVideoSourceRequest

But first inspect whether similar Request classes already exist.

Do not duplicate existing validation logic.

Validation must include:

- required fields
- string lengths
- numeric ranges
- valid enum values
- valid relationships
- valid URLs
- unique slug where appropriate

============================================================

# 19. SLUG HANDLING

============================================================

Inspect current slug architecture.

If slugs already exist:

- Preserve existing slugs when editing unless explicitly changed.
- Prevent duplicate slugs.
- Ensure public routes continue working.

DO NOT break:

/watch/movies/{slug}

/watch/series/{slug}/{episode}

/watch/anime/{slug}/{episode}

/watch/donghua/{slug}/{episode}

Any slug change must be handled safely.

============================================================

# 20. IMAGE / POSTER HANDLING

============================================================

Before implementing uploads:

Inspect how the existing project currently handles:

- poster
- backdrop
- thumbnail

If storage is already implemented:

REUSE IT.

Do not create a second upload system.

If upload functionality does not exist yet, implement it cleanly using Laravel Storage.

Validate:

- MIME type
- file size
- image dimensions if appropriate

Do not allow arbitrary executable files.

If the project currently stores external image URLs rather than files, preserve that architecture unless Phase 7 explicitly requires migration.

============================================================

# 21. TESTING REQUIREMENTS

============================================================

Create comprehensive Pest tests.

Suggested files:

tests/Feature/Admin/AdminDashboardTest.php

tests/Feature/Admin/ContentManagementTest.php

tests/Feature/Admin/GenreManagementTest.php

tests/Feature/Admin/SeasonManagementTest.php

tests/Feature/Admin/EpisodeManagementTest.php

tests/Feature/Admin/VideoSourceManagementTest.php

tests/Feature/Admin/PublishContentTest.php

tests/Feature/Admin/AdminAuthorizationTest.php

Test at minimum:

---

## AUTHORIZATION

- Guest cannot access admin
- Normal user cannot access admin
- Admin can access admin

---

## CONTENT

- Admin can create content
- Admin can edit content
- Admin can delete content
- Search works
- Filters work
- Pagination works
- Validation works
- Duplicate slug is rejected

---

## PUBLISHING

- Admin can publish content
- Admin can unpublish content
- Unpublished content remains inaccessible publicly
- Published content remains accessible according to existing rules

---

## SEASONS

- Admin can create season
- Admin can update season
- Admin can delete season
- Season belongs to correct content

---

## EPISODES

- Admin can create episode
- Admin can update episode
- Admin can delete episode
- Episode belongs to correct season
- Episode ordering works

---

## VIDEO SOURCES

- Admin can create source
- Admin can update source
- Admin can delete source
- Source belongs to correct content/episode
- Unauthorized users cannot modify sources
- Invalid URLs are rejected if URL validation applies

---

## SECURITY

Ensure a normal user cannot manipulate admin endpoints by manually submitting requests.

============================================================

# 22. REGRESSION TESTING

============================================================

Before Phase 7:

Current status:

86 tests passed

After Phase 7:

Run:

php artisan test

ALL existing tests must continue to pass.

Do not remove existing tests.

Do not weaken existing assertions simply to make tests pass.

Do not modify tests merely to hide regressions.

If an existing test fails:

1. Identify why.
2. Determine whether Phase 7 caused the regression.
3. Fix the implementation.
4. Re-run the test.

============================================================

# 23. ADDITIONAL COMMANDS

============================================================

Run:

php artisan route:list

Check:

- route collisions
- route names
- middleware
- parameter binding

Run:

php artisan migrate:status

if database changes are introduced.

Run:

php artisan optimize:clear

after major changes.

Then:

php artisan test

============================================================

# 24. IMPORTANT ARCHITECTURE RULES

============================================================

Controllers should remain thin.

Business logic should live in:

- Services
- Models
- Form Requests
- Policies

where appropriate.

Do not put large blocks of business logic inside Blade templates.

Do not put database queries directly inside Blade.

Do not use raw SQL unless genuinely necessary.

Do not duplicate existing services.

Do not duplicate existing models.

Do not duplicate existing relationships.

============================================================

# 25. DO NOT CHANGE PHASE 6 VIDEO PLAYER

============================================================

The Phase 6 video player is already working.

DO NOT unnecessarily rewrite:

WatchController

watch.blade.php

WatchHistoryService

unless Phase 7 absolutely requires a compatible modification.

The following must continue working:

- Movie playback
- Series playback
- Anime playback
- Donghua playback
- Resume playback
- Progress synchronization
- Next episode
- Previous episode
- Auto-next
- Episode sidebar
- Published-content authorization

============================================================

# 26. DO NOT BREAK USER FEATURES

============================================================

The following Phase 5 features MUST continue working:

- Favorites
- Watchlist
- Watch History
- Continue Watching
- Ratings
- Progress tracking

Do not change their behavior unless required for compatibility.

============================================================

# 27. IMPLEMENTATION PROCESS

============================================================

Follow this sequence:

STEP 1
Inspect the existing project.

STEP 2
Map existing:

- Content
- Genre
- Season
- Episode
- VideoSource
- User
- Rating
- Favorite
- Bookmark
- WatchHistory

STEP 3
Inspect existing admin authorization.

STEP 4
Inspect existing routes.

STEP 5
Inspect existing Blade layouts/components.

STEP 6
Inspect existing migrations.

STEP 7
Create a concise implementation plan based on the ACTUAL codebase.

STEP 8
Implement Phase 7 incrementally.

Recommended order:

1. Admin layout/navigation
2. Admin dashboard
3. Content CRUD
4. Publishing
5. Genre management
6. Season management
7. Episode management
8. Video source management
9. Search/filter/pagination
10. Validation/security
11. Tests
12. Regression verification

============================================================

# 28. FINAL VERIFICATION

============================================================

At the end execute:

php artisan optimize:clear

php artisan route:list

php artisan test

The final test result must show:

- All previous 86 tests passing
- All newly created Phase 7 tests passing
- ZERO failures

Also verify:

- No route collision
- No syntax errors
- No migration errors
- No N+1 obvious issues
- No unauthorized admin access
- No broken public routes
- No broken watch routes
- No broken favorites/watchlist/history
- Dark mode works
- Light mode works
- Responsive admin UI works

============================================================

# 29. FINAL REPORT

============================================================

After implementation, provide a completion report containing:

1. Files created
2. Files modified
3. Database/migration changes
4. Routes added
5. Controllers added
6. Services added
7. Policies/Form Requests added
8. UI pages/components added
9. Security changes
10. Tests added
11. Previous test count
12. Final test count
13. Exact result of:

php artisan test

14. Any remaining TODOs or known limitations

IMPORTANT:

Do NOT claim Phase 7 is complete unless the implementation actually exists and the tests have actually been executed successfully.

============================================================

# FINAL OBJECTIVE

============================================================

When Phase 7 is complete, an administrator should be able to manage the entire NontonKu catalog from the Admin Panel:

Content
↓
Genres
↓
Seasons
↓
Episodes
↓
Video Sources
↓
Publishing

while normal users continue to use:

Home
Catalog
Search
Favorites
Watchlist
History
Continue Watching
Ratings
Video Player

without any regression.

Start by inspecting the existing NontonKu codebase and then implement Phase 7 incrementally.
