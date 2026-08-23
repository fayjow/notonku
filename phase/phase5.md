# NontonKu — Phase 5 Implementation Plan

## User Features: Favorites, Watchlist, Watch History, Ratings & Continue Watching

You are working on the existing Laravel project:

Project:
NontonKu

Current status:

- Laravel application is already implemented through Phase 4.
- Phase 1–4 functionality is working.
- Public catalog, detail pages, authentication, database relationships, search, pagination, sorting, SEO, dark/light mode, and type-safe content routing are already implemented.
- Do NOT rewrite or unnecessarily refactor existing Phase 1–4 functionality.

Current verification result:

php artisan test

Tests: 52 passed (126 assertions)
Duration: ~2.47s

This is the baseline that MUST remain passing after Phase 5.

---

# IMPORTANT DEVELOPMENT RULES

1. Inspect the existing project before making changes.
2. Reuse existing models, relationships, enums, services, Blade components, layouts, routes, middleware, and database structures wherever possible.
3. Do NOT create duplicate tables or duplicate functionality if the required database structure already exists.
4. Do NOT modify existing migrations unless absolutely necessary.
5. Do NOT break any Phase 1–4 routes or tests.
6. Follow the existing Laravel coding style and architecture.
7. Use Eloquent relationships instead of raw SQL whenever practical.
8. Use Form Requests or controller validation where appropriate.
9. All user-specific actions MUST require authentication.
10. Users must NEVER be able to modify another user's favorites, bookmarks, watch history, or ratings.
11. All state-changing requests must use CSRF protection.
12. Prevent duplicate favorites, bookmarks, and ratings according to the existing database constraints.
13. Use eager loading to prevent N+1 queries.
14. Do not expose unnecessary user information to the frontend.
15. Do not introduce arbitrary hex colors. Continue using the existing Tailwind design system.
16. Maintain the existing responsive mobile-first design.
17. Maintain dark/light mode compatibility.
18. Maintain accessibility:
    - aria-label where appropriate
    - keyboard navigation
    - focus-visible states
    - sufficient contrast
    - reduced-motion support
19. Do not add unnecessary JavaScript dependencies.
20. Prefer Alpine.js if interactive behavior is needed because it is already used by the project.
21. Run tests after implementation.
22. If a test fails, diagnose and fix the actual cause instead of weakening/removing the test.
23. Do not mark Phase 5 complete until all tests pass.

---

# PHASE 5 OBJECTIVE

Implement authenticated user features:

1. Favorites
2. Watchlist / Episode Bookmarks
3. Watch History
4. Ratings
5. Continue Watching
6. User-specific UI states
7. Comprehensive automated testing

The result should allow this user flow:

Guest:
Browse catalog
→ Open content detail
→ Login required for user features

Authenticated user:
Login
→ Browse content
→ Favorite content
→ Bookmark episodes
→ Watch content
→ Save playback progress
→ Resume watching
→ Rate content
→ View favorites/watchlist/history
→ Continue watching from homepage

---

# STEP 1 — INSPECT EXISTING DATABASE AND MODELS

Before writing code, inspect:

- app/Models/Content.php
- app/Models/Episode.php
- app/Models/Season.php
- app/Models/User.php
- existing rating-related models
- existing favorite relationships
- existing bookmark relationships
- existing watch history models
- existing WatchHistoryService
- migrations related to:
    - favorites
    - bookmarks
    - ratings
    - watch history
- existing controllers
- existing routes/web.php
- existing Blade components
- existing public layouts
- existing homepage/detail views

Determine exactly what functionality already exists.

The project already has tests indicating:

- User can favorite content and prevents duplicates
- User can bookmark episodes and prevents duplicates
- Rating relationship exists
- WatchHistoryService exists

Therefore DO NOT blindly recreate these structures.

Instead, extend the existing implementation into a complete public user-facing feature.

---

# STEP 2 — FAVORITES

Implement a complete Favorite feature.

Expected behavior:

Authenticated user can:

- favorite a Content
- remove a Content from favorites
- see whether a Content is already favorited
- view their favorite content list

Suggested routes:

GET /favorites
POST /favorites/{content}
DELETE /favorites/{content}

Use named routes such as:

favorites.index
favorites.store
favorites.destroy

All favorite mutation routes must use auth middleware.

Use route model binding where appropriate.

Prevent duplicate favorites.

The database should remain the ultimate protection against duplicates.

---

# FAVORITE UI

Update:

resources/views/components/content-card.blade.php

and:

resources/views/public/show.blade.php

Add a favorite button.

Guest behavior:

- Show login prompt/link or require login when clicking favorite.

Authenticated behavior:

- Show active favorite state.
- Show inactive state when not favorited.
- Allow toggling.

Use Alpine.js only if needed.

Do not reload the entire page unnecessarily if a simple Alpine interaction can handle the visual state.

However, correctness and maintainability are more important than excessive AJAX optimization.

The favorite button MUST have:

aria-label

and clear focus-visible styling.

---

# FAVORITES PAGE

Create:

resources/views/public/favorites.blade.php

Use the existing:

<x-container>
<x-section-heading>
<x-content-card>
<x-empty-state>

Design:

- Same visual language as the existing catalog.
- Responsive grid.
- Dark/light compatible.
- Pagination if necessary.
- Empty state when the user has no favorites.

Do not duplicate catalog card markup.

---

# STEP 3 — EPISODE WATCHLIST / BOOKMARKS

The existing database already supports episode bookmarks.

Expose this functionality to users.

Suggested routes:

GET /watchlist
POST /watchlist/{episode}
DELETE /watchlist/{episode}

Named:

watchlist.index
watchlist.store
watchlist.destroy

Authentication required.

Users can only bookmark episodes for themselves.

Prevent duplicates.

Create:

resources/views/public/watchlist.blade.php

Display:

- series/content title
- season
- episode number
- episode title
- thumbnail if available
- link to the episode/content playback page if such route currently exists

IMPORTANT:

Do NOT invent a video player route if Phase 5 does not yet have a playback/player implementation.

If no playback route exists yet, provide a safe link to the content detail page.

---

# STEP 4 — WATCH HISTORY

There is already:

App\Services\WatchHistoryService

and existing tests proving:

- movie progress can be updated
- progress near duration is marked completed
- episode progress can be updated

Do NOT replace the existing service.

Inspect and extend it only if necessary.

Implement a public-facing history page:

GET /history

Named route:

history.index

Authentication required.

Create:

resources/views/public/history.blade.php

Display:

- movie or series title
- episode title when applicable
- progress
- duration
- percentage
- last watched timestamp
- completed status

Use progress bars.

Example:

████████░░ 80%

Do not expose records belonging to another user.

Use pagination if appropriate.

---

# STEP 5 — WATCH PROGRESS ENDPOINT

If the current project does not already expose an HTTP endpoint for updating playback progress, create one.

Suggested:

POST /watch-history

Named:

watch-history.store

Authentication required.

Expected request:

content_id OR episode_id
progress_seconds
duration_seconds

Validation:

- progress_seconds must be numeric
- progress_seconds >= 0
- duration_seconds must be numeric
- duration_seconds > 0
- progress_seconds must not exceed duration_seconds
- content/episode must exist
- published content/episode rules must be respected where appropriate

Use the existing WatchHistoryService.

Do not put progress business logic directly into the controller.

The service should remain responsible for:

- creating/updating history
- calculating completion
- preventing invalid progress
- updating timestamps

If the existing service already handles these correctly, preserve it.

---

# STEP 6 — CONTINUE WATCHING

Update:

resources/views/public/home.blade.php

For authenticated users, add:

"Continue Watching"

section.

Only show it when there is history.

Display:

- poster/thumbnail
- title
- episode information if applicable
- progress bar
- percentage
- last watched information
- continue/resume action

For guests:

Do not query or display user-specific history.

This is important for both performance and privacy.

HomeController should retrieve this data efficiently.

Avoid N+1 queries.

Use eager loading.

Limit the number of continue-watching items, for example 6 or 12.

Sort by most recently watched.

Completed items should either:

- be excluded from Continue Watching

or

- only appear if the existing application design explicitly supports replay.

Prefer excluding completed items from Continue Watching.

---

# STEP 7 — RATINGS

The existing database already has rating functionality.

Inspect the current Rating model and relationship.

Implement a public user rating interface.

Suggested route:

POST /content/{content}/rating

Named:

ratings.store

Authentication required.

Expected rating:

1–10

Validation:

rating:
required
integer
min:1
max:10

Each user can only have one rating per content.

If the user already rated:

- update their existing rating

Do not create a duplicate.

The database constraint must remain intact.

---

# RATING UI

On:

resources/views/public/show.blade.php

Display:

- average rating
- number of ratings
- current user's rating if authenticated

Example:

★ 8.4
1,245 ratings

Authenticated user:

"Your rating: 9/10"

Allow changing the rating.

Guest:

Show login prompt instead of submitting.

Use accessible controls.

If using buttons for ratings, ensure they have appropriate aria-label values.

---

# STEP 8 — CONTENT CARD

Update:

resources/views/components/content-card.blade.php

The card should support:

- favorite state
- average rating
- existing poster/title/year/type/age
- favorite button

Do NOT turn the component into a giant component with excessive business logic.

Prefer receiving already-prepared model data/state.

Avoid running database queries directly inside Blade components.

---

# STEP 9 — USER DATA SECURITY

This section is critical.

Verify:

User A cannot:

- delete User B's favorite
- delete User B's bookmark
- update User B's watch history
- update User B's rating

Controllers must use authenticated user relationships.

For example, prefer:

auth()->user()->favorites()->...

instead of:

Favorite::find($id)

followed by trusting user input.

Never trust a user-supplied user_id.

Never accept user_id from request data for these operations.

Always derive the user from the authenticated session.

---

# STEP 10 — CONTROLLERS

Create or extend controllers as appropriate.

Suggested:

app/Http/Controllers/FavoriteController.php
app/Http/Controllers/WatchlistController.php
app/Http/Controllers/WatchHistoryController.php
app/Http/Controllers/RatingController.php

Do not create unnecessary controllers if the existing architecture already has an appropriate controller.

Keep controllers thin.

Business logic should remain in:

- models
- relationships
- services

where appropriate.

---

# STEP 11 — ROUTES

Update:

routes/web.php

Organize authenticated user routes clearly.

Suggested structure:

Route::middleware('auth')->group(function () {

    // Favorites
    ...

    // Watchlist
    ...

    // History
    ...

    // Ratings
    ...

    // Watch progress
    ...

});

Do not break:

home
movies
series
anime
donghua
search
content detail
authentication
admin
profile

routes.

Check route names for collisions.

Run:

php artisan route:list

and inspect the result.

---

# STEP 12 — TESTING

Create or extend tests.

Recommended files:

tests/Feature/FavoriteTest.php
tests/Feature/WatchlistTest.php
tests/Feature/WatchHistoryFeatureTest.php
tests/Feature/RatingFeatureTest.php
tests/Feature/ContinueWatchingTest.php

If similar existing tests already exist, extend them rather than duplicating them.

---

# REQUIRED TEST CASES

## Favorites

Test:

1. Guest cannot favorite content.
2. Authenticated user can favorite.
3. User can remove favorite.
4. Duplicate favorite is prevented.
5. User can only see their own favorites.
6. Favorite page works.
7. Favorite state is displayed correctly.

## Watchlist

Test:

1. Guest cannot bookmark.
2. Authenticated user can bookmark episode.
3. User can remove bookmark.
4. Duplicate bookmark is prevented.
5. User can only see their own bookmarks.
6. Watchlist page works.

## Watch History

Test:

1. Guest cannot update history.
2. Authenticated user can save progress.
3. Existing history is updated instead of duplicated.
4. Progress cannot be negative.
5. Progress cannot exceed duration.
6. Near-complete progress marks content completed according to existing service behavior.
7. User cannot modify another user's history.
8. History page only shows current user's records.

## Ratings

Test:

1. Guest cannot rate.
2. Authenticated user can rate.
3. Rating must be 1–10.
4. Invalid rating is rejected.
5. Duplicate rating is prevented.
6. Existing rating can be updated.
7. Average rating is calculated correctly.
8. User cannot modify another user's rating.

## Continue Watching

Test:

1. Guest homepage does not query user history.
2. Authenticated user sees continue watching.
3. Completed items are excluded.
4. Continue watching is sorted by latest activity.
5. Continue watching is limited.
6. User only sees their own history.

---

# STEP 13 — PERFORMANCE

Check for N+1 queries.

Especially inspect:

- favorites
- bookmarks
- watch history
- ratings
- homepage continue watching
- content detail page

Use:

with()
withCount()
withAvg()
whereHas()
when()

where appropriate.

Do not load entire datasets unnecessarily.

Use pagination for pages with potentially large datasets.

---

# STEP 14 — UI / UX

All new pages must use the existing design system.

Reuse:

<x-container>
<x-section-heading>
<x-content-card>
<x-badge>
<x-alert>
<x-empty-state>

Do not create duplicate card designs.

Maintain:

- Tailwind
- Alpine.js
- dark mode
- light mode
- mobile-first layout
- responsive grid
- focus rings
- accessible buttons
- reduced motion

Use existing colors:

- neutral backgrounds
- zinc dark surfaces
- indigo primary accent

Do not introduce arbitrary hex colors.

---

# STEP 15 — SEO

User-specific pages such as:

/favorites
/watchlist
/history

should NOT be indexed by search engines.

Use appropriate:

robots noindex

metadata.

Do not expose private user information in SEO metadata.

Content detail pages must retain the existing Phase 4 SEO implementation.

---

# STEP 16 — VERIFICATION

After implementation run:

php artisan test

Then:

php artisan route:list

Then:

php artisan optimize:clear

Then:

php artisan test

The final result MUST show:

0 failed

All existing 52 tests must remain passing.

The new Phase 5 tests must also pass.

Do NOT remove or weaken existing tests to make the suite pass.

---

# PHASE 5 ACCEPTANCE CRITERIA

Phase 5 is complete only when:

[ ] Favorites work
[ ] Favorite toggle works
[ ] Favorites page works
[ ] Episode bookmarks work
[ ] Watchlist page works
[ ] Watch history works
[ ] Progress validation works
[ ] Continue Watching works
[ ] Ratings work
[ ] Rating update works
[ ] Average rating works
[ ] Guest authorization is enforced
[ ] User ownership is enforced
[ ] No duplicate favorites
[ ] No duplicate bookmarks
[ ] No duplicate ratings
[ ] No N+1 problems introduced
[ ] Dark mode works
[ ] Light mode works
[ ] Mobile layout works
[ ] Accessibility maintained
[ ] Existing Phase 1–4 functionality remains intact
[ ] All tests pass

---

# FINAL REPORT FORMAT

When finished, provide a concise completion report with:

## Phase 5 Completion Report

### 1. Models

List modified models and relationships.

### 2. Controllers

List created/modified controllers.

### 3. Services

List created/modified services.

### 4. Routes

List new routes.

### 5. Views

List created/modified Blade views/components.

### 6. Features

Summarize:

- Favorites
- Watchlist
- Watch History
- Ratings
- Continue Watching

### 7. Security

Explain authentication, authorization, ownership protection, validation, and CSRF.

### 8. Performance

Explain eager loading, pagination, and query optimization.

### 9. Tests

Show exact test result:

Tests: XX passed (XXX assertions)

### 10. Warnings/Errors

List any remaining issues.

### 11. Files Changed

Separate:

- Modified
- Created

Do not claim Phase 5 is complete unless the final test suite is green.
