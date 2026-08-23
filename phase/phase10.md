NontonKu — Phase 10 Implementation Plan: Discovery, Recommendations & Advanced User Experience
Goal Description

Phase 10 akan berfokus pada peningkatan pengalaman pengguna NontonKu setelah fondasi keamanan, streaming, SEO, performance, admin management, dan user features dari Phase 1–9 telah selesai.

Current project status:

Phase 1–9 completed.
Existing functionality must remain intact.
Current automated test suite: 119 tests passed.
Do NOT rewrite or break existing architecture.
All existing tests must continue passing after Phase 10.
Follow the existing Laravel architecture, Blade, Tailwind CSS, Alpine.js, Pest, Eloquent relationships, Form Requests, middleware, and existing route conventions.

The main objective of Phase 10 is to make NontonKu feel like a modern streaming platform rather than simply a catalog + video player.

IMPORTANT RULES

Before modifying anything:

Inspect the existing project structure.
Inspect:
routes/web.php
app/Models/_
app/Http/Controllers/_
app/Services/_
resources/views/layouts/_
resources/views/public/_
resources/views/components/_
existing migrations
existing Pest tests
Reuse existing relationships and database columns whenever possible.
Do NOT create a migration unless absolutely necessary.
Do NOT duplicate existing functionality.
Do NOT remove working Phase 1–9 features.
Do NOT replace the existing video player architecture.
Do NOT introduce React/Vue/Inertia unless absolutely required. Continue using Blade + Alpine.js.
Do not use fake/mock production data in the application.
All user-specific queries must use the authenticated user's ID from the session.
Public catalog must never expose unpublished content.
Preserve the existing dark/light mode implementation.
Maintain mobile responsiveness.
Avoid N+1 queries.
Every new feature must have Pest tests.
Phase 10 Features

1. Advanced Homepage Discovery

Improve the homepage into a streaming-platform-style discovery page.

Modify

app/Http/Controllers/HomeController.php

resources/views/public/home.blade.php

Add sections

For guests:

Hero/banner section
Latest Movies
Latest Series
Latest Anime
Latest Donghua
Popular content
Recently added
Genre discovery

For authenticated users:

Continue Watching
My Favorites
My Watchlist
Recently Watched
Recommended For You
Rules

Do not display empty sections.

For example:

Continue Watching
[only shown if user has unfinished history]

My Favorites
[only shown if user has favorites]

Recommended For You
[only shown if recommendation data exists]

Use efficient queries and eager loading.

Do not load the entire database into PHP just to create sections.

2. Popular Content

Create a lightweight popularity system based on existing database information.

Do NOT introduce a complex machine-learning recommendation system.

Use existing information such as:

ratings
ratings_count
watch history
favorites
recent activity

Create a deterministic popularity score.

Example concept:

popularity_score =
rating_weight + rating_count_weight + watch_count_weight + favorite_count_weight

The exact formula should be based on the existing schema after inspection.

Do not invent database columns if equivalent existing relationships already exist.

3. Recommendation Engine

Create a simple rule-based recommendation system.

NEW

app/Services/RecommendationService.php

The service should recommend content based on:

Genres from user's favorites.
Genres from user's watch history.
Content types the user watches most.
Highly rated content.
Popular content.

Example:

If the user frequently watches:

Action
Adventure
Anime

recommend published content matching those characteristics.

Requirements
Never recommend unpublished content.
Never recommend content the user already completed if sufficient alternatives exist.
Never recommend content already in favorites if sufficient alternatives exist.
Avoid duplicates.
Limit recommendations to a reasonable number such as 10.
Guests receive generic popular recommendations.
Authenticated users receive personalized recommendations.

Do not use external AI APIs.

4. "Because You Watched..." Section

Add a contextual recommendation section.

Example:

Because You Watched Naruto

[Anime A] [Anime B] [Anime C] [Anime D]

The source content should come from the user's recent watch history.

Only show this section when enough information exists.

5. Content Detail Page Improvements

Modify:

resources/views/public/show.blade.php

Add:

Related Content
You May Also Like

Recommendations should be based primarily on:

shared genres
same content type
popularity/rating
More information

Improve the content detail page with:

rating
genres
release date
status
duration
description
seasons/episodes
available streaming sources where appropriate

Do not expose private/admin-only information.

6. Advanced Search Experience

Improve the existing search page.

Support:

/search?q=naruto
/search?q=naruto&type=anime
/search?q=naruto&genre=action
/search?q=naruto&sort=latest
/search?q=naruto&sort=rating

Available filters should be based on existing enum/database values.

Filters
Keyword
Type
Genre
Status
Sort

Sort options:

Latest
Oldest
Highest Rated
Most Popular
A-Z

All search results must:

only show published content
paginate
preserve query parameters
avoid SQL injection
avoid N+1 queries 7. Search UI

Improve the search interface.

Add:

search input
search button
filter dropdown
genre dropdown
sorting dropdown
result count
empty-state UI
pagination

Example:

Search results for "Naruto"

124 results

Type: [Anime ▼]
Genre: [Action ▼]
Sort: [Most Popular ▼]

---

[Naruto] [Naruto Shippuden] ...

Maintain dark/light mode.

8. User Profile Improvements

Improve:

resources/views/profile/\*

Add a cleaner user dashboard containing:

Profile
Favorites
Watchlist
Watch History
Continue Watching
Ratings

Display useful statistics:

Movies Watched
Episodes Watched
Favorites
Watchlist
Ratings Given

Use aggregate queries instead of loading entire collections.

9. My Ratings Page

Create:

/ratings

New Controller

app/Http/Controllers/User/RatingController.php

If an existing controller already handles ratings, extend it rather than duplicating it.

Create:

resources/views/public/ratings.blade.php

Display:

My Ratings

Poster | Title | My Rating | Average Rating | Date

Users should only see their own ratings.

Guests must be redirected to login.

10. Improved Continue Watching

Improve the existing Continue Watching implementation.

Each item should display:

Poster
Title
Episode
Progress bar
Progress percentage
Resume button
Remove button

Example:

Naruto
Episode 12

████████████░░░░ 72%

Continue Watching

Add a way to remove an item from Continue Watching/history if the existing architecture allows it safely.

Do not accidentally delete unrelated user history.

11. "Recently Added" System

Add a section/page:

Recently Added

Sorted by:

created_at DESC

Only published content should appear.

Add pagination where appropriate.

12. Genre Discovery Page

Create:

/genres

Display all available genres.

Clicking a genre should lead to:

/genres/{genre:slug}

Example:

Action
Adventure
Comedy
Drama
Fantasy
Romance
Sci-Fi

Genre pages should display:

genre title
description if available
content count
published content
pagination
sorting 13. Content SEO Improvements

Extend Phase 9 SEO without breaking existing metadata.

For content pages:

Generate dynamic:

<title>
<meta name="description">
<meta property="og:title">
<meta property="og:description">
<meta property="og:image">
<meta property="og:type">
<meta property="og:url">
<link rel="canonical">

JSON-LD should continue using real database data.

Do NOT fabricate:

actors
directors
ratings
dates
production companies
URLs

Only output information that actually exists in the database.

14. Breadcrumb Navigation

Add accessible breadcrumbs to:

Search
Genre
Content detail
Watch page
Favorites
Watchlist
History

Example:

Home / Anime / Naruto / Episode 12

Use semantic HTML:

<nav aria-label="Breadcrumb">
15. Accessibility Improvements

Audit the public UI.

Ensure:

buttons have accessible labels
images have meaningful alt
icon-only buttons use aria-label
keyboard navigation works
focus states are visible
sufficient contrast in light/dark mode
forms have labels
errors are understandable

Pay special attention to:

video controls
favorite button
bookmark button
rating controls
source switcher
mobile navigation 16. Loading & Empty States

Add polished loading/empty states.

Examples:

Empty Favorites
Your Favorites is Empty

Start adding movies and series you love.

[Explore Content]
Empty Watchlist
Your Watchlist is Empty
Empty History
No Watch History Yet
Search Empty
No results found for "Naruto"

Do not display broken layouts when data is empty.

17. Error Handling

Verify that Phase 9 error pages continue working.

Test:

404
403
419
429
500

Ensure errors do not expose:

stack traces
SQL queries
filesystem paths
environment variables
sensitive configuration 18. Performance Audit

Perform another performance review.

Inspect:

HomeController
SearchController
Content detail controller
Genre controller
WatchController
User history queries
RecommendationService

Look specifically for:

N+1 queries
unnecessary collection loading
repeated database queries
missing eager loading
large unbounded queries

Use:

with()
withCount()
withAvg()
select()
paginate()
limit()

where appropriate.

Do not blindly cache user-specific data.

19. Database Query Optimization

Where appropriate, verify that frequently queried columns have indexes.

Before creating any migration:

Inspect current migrations.
Determine whether an index already exists.
Only create an index if there is a demonstrated benefit.
Never duplicate indexes.

Potential candidates should be evaluated rather than automatically created:

contents.slug
contents.type
contents.is_published
contents.created_at
genres.slug
episodes.season_id
video_sources.sourceable_id
video_sources.sourceable_type
watch_histories.user_id
ratings.user_id 20. Security Review

Perform a complete Phase 10 security review.

Check for:

IDOR

Verify that users cannot access:

another user's favorites
another user's history
another user's ratings
another user's watchlist
Mass Assignment

Inspect:

$fillable
$guarded
XSS

Verify all user-generated content is escaped.

Avoid unsafe:

{!! $userInput !!}

unless explicitly sanitized.

CSRF

All state-changing requests must use CSRF protection.

Authorization

Admin routes must remain protected.

Published Content

Public routes must never expose unpublished content.

File Security

Uploads must continue enforcing:

MIME validation
size limits
safe storage
safe file names 21. Admin Dashboard Refinement

Improve the existing admin dashboard.

Display:

Total Content
Movies
Series
Anime
Donghua
Episodes
Genres
Users
Video Sources
Watch History
Ratings

Add recent activity sections where the existing schema supports it.

Example:

Recently Added Content
Recently Added Episodes
Recent Users

Do not load huge datasets.

Use:

latest()->limit(10)

or equivalent.

22. Admin UI Consistency

Audit all Admin pages:

Dashboard
Content
Genres
Seasons
Episodes
Video Sources
Users

Ensure consistent:

spacing
buttons
forms
tables
pagination
alerts
validation errors
dark mode
responsive layout

Do not redesign the entire admin panel unnecessarily.

Improve inconsistencies instead.

23. Automated Tests

Create the following tests where they do not already exist.

RecommendationTest.php

Test:

guest recommendations
authenticated recommendations
genre-based recommendations
unpublished content exclusion
duplicate prevention
completed-content exclusion
SearchFilterTest.php

Test:

keyword search
type filter
genre filter
sorting
pagination
unpublished content exclusion
GenrePageTest.php

Test:

genre list
genre detail
slug resolution
published content only
pagination
missing genre → 404
RatingPageTest.php

Test:

guest protection
user sees only own ratings
rating data correctness
ContinueWatchingTest.php

Extend existing tests for:

progress display
resume URL
completed items exclusion
user isolation
removal behavior
RecommendationSecurityTest.php

Test:

user A cannot access user B recommendation data
unpublished content is never recommended
no private information leaks
AccessibilityTest.php

Test important semantic/accessibility markers where practical.

PerformanceTest.php

Do not rely on fragile exact query counts unless necessary.

Test critical behavior and avoid tests that become brittle due to harmless query changes.

24. Full Regression Test

After implementation run:

php artisan optimize:clear
php artisan route:list
php artisan test

The original 119 tests must remain passing.

Expected result should be:

Tests: xxx passed
Assertions: xxx

No existing test may be disabled, skipped, deleted, or modified merely to make the suite pass.

25. Manual Verification

After all automated tests pass, manually verify:

Guest
Homepage
Search
Genre
Content detail
Login redirect
Public SEO metadata
Authenticated User
Continue Watching
Favorites
Watchlist
History
Ratings
Recommendations
Profile
Search filters
Admin
Dashboard
Content CRUD
Genre CRUD
Season CRUD
Episode CRUD
Video Source CRUD
Image uploads
Video Player

Test:

MP4
HLS
Embed
Source switching
Resume playback
Keyboard shortcuts
Fullscreen
Mute
Seek
Next episode
Previous episode
Responsive

Test:

Desktop
Tablet
Mobile
Theme

Test:

Light mode
Dark mode 26. Final Phase 10 Completion Report

When finished, provide a structured completion report containing:

# Phase 10 Completion Report

## 1. Features Implemented

## 2. Files Created

## 3. Files Modified

## 4. Database/Migration Changes

## 5. Routes Added/Modified

## 6. Controllers Added/Modified

## 7. Services Added/Modified

## 8. Security Improvements

## 9. Performance Improvements

## 10. SEO Improvements

## 11. UI/UX Improvements

## 12. Tests Added

## 13. Previous Test Count

119

## 14. Final Test Count

## 15. Exact php artisan test Result

## 16. Known Limitations / TODO

## 17. Manual Verification Result

CRITICAL FINAL INSTRUCTION

Do not stop after writing the implementation plan.

Actually implement Phase 10 in the existing NontonKu project.

Work incrementally:

Inspect existing architecture.
Implement one feature group.
Run relevant tests.
Fix failures.
Continue to the next feature.
Run the complete test suite.
Run php artisan optimize:clear.
Run php artisan route:list.
Verify that all existing Phase 1–9 functionality remains intact.
Provide the final completion report only after the implementation and tests are actually complete.

Do not claim a feature is implemented unless you have actually modified the project and verified it.

Do not create unnecessary migrations.

Do not break the existing 119 passing tests.
