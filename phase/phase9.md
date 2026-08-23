# NontonKu — PHASE 9

## Production Hardening, SEO, Performance & Final Platform Refinement

You are continuing development of the existing Laravel project:

NontonKu

IMPORTANT:
Phase 1–8 have already been completed successfully.

Current test baseline:

108 tests passed
249 assertions

The application currently supports:

- Authentication
- Email verification
- Admin authorization
- Movies
- Series
- Anime
- Donghua
- Genres
- Seasons
- Episodes
- Favorites
- Watchlist
- Watch History
- Continue Watching
- Ratings
- Custom HTML5 video player
- MP4 streaming
- HLS streaming using HLS.js
- Embed/Iframe streaming
- Multiple video source switching
- Secure source selection
- IDOR protection
- Admin Content Management
- Admin Genre Management
- Admin Season Management
- Admin Episode Management
- Admin Video Source Management
- Admin image uploads
- Dark/Light mode
- Search
- Pagination
- Episode navigation
- Resume playback
- Auto-next episode
- MorphMap for Content/Episode video sources

Phase 8 is fully complete and currently has:

108 tests passed
249 assertions

==================================================
PHASE 9 OBJECTIVE
==================================================

Phase 9 focuses on making NontonKu more production-ready.

Main objectives:

1. Improve streaming reliability
2. Improve video source management
3. Add secure download configuration
4. Improve watch history reliability
5. Improve search
6. Improve SEO
7. Improve security
8. Improve admin dashboard
9. Improve performance
10. Improve error pages
11. Improve overall UI/UX
12. Maintain zero regressions

==================================================
IMPORTANT RULES
==================================================

DO NOT rewrite the application.

DO NOT replace Blade with React/Vue.

DO NOT replace Alpine.js unnecessarily.

DO NOT create unnecessary migrations.

DO NOT duplicate existing functionality.

DO NOT remove existing tests.

DO NOT disable CSRF.

DO NOT weaken authorization.

DO NOT expose arbitrary URLs.

DO NOT expose filesystem paths.

DO NOT trust user_id from request data.

Always derive the user from the authenticated session.

Reuse the existing architecture whenever possible.

Before modifying anything, inspect the existing project.

==================================================
STEP 1 — INSPECT THE EXISTING PROJECT
==================================================

Before implementation, inspect:

- app/Models/\*
- app/Http/Controllers/\*
- app/Http/Controllers/Admin/\*
- app/Http/Requests/\*
- app/Services/\*
- routes/web.php
- database/migrations/\*
- resources/views/\*
- tests/\*
- AppServiceProvider
- WatchController
- WatchHistoryService
- VideoSource model
- Content model
- Episode model

Determine:

1. What already exists.
2. What Phase 9 requirements are already implemented.
3. What is missing.
4. What can be reused.
5. Whether a migration is genuinely necessary.

DO NOT make changes before completing this inspection.

==================================================
STEP 2 — STREAMING SOURCE IMPROVEMENTS
==================================================

Improve the existing VideoSource system.

Existing source types:

- mp4
- hls
- embed

Maintain the existing provider mapping.

Ensure source selection follows:

1. Requested source_id if valid
2. Source must belong to current Content/Episode
3. Source must be active
4. Current Content/Episode must be published
5. Otherwise fallback to highest-priority active source

Never allow:

- inactive source access
- source belonging to another content
- source belonging to another episode
- unpublished source access
- IDOR through source_id

Do not regress the existing Phase 8 IDOR protection.

==================================================
STEP 3 — VIDEO PLAYER IMPROVEMENTS
==================================================

Improve:

resources/views/public/watch.blade.php

Keep the existing custom Alpine.js + HTML5 player.

Add/improve:

- Playback speed
- Volume
- Mute
- Fullscreen
- Progress bar
- Buffered indicator
- Keyboard shortcuts
- Source switching
- Loading state
- Error state

Keyboard shortcuts:

Space = Play/Pause
F = Fullscreen
M = Mute
Left Arrow = seek backward
Right Arrow = seek forward

Do not trigger shortcuts while the user is typing in an input/select/textarea.

==================================================
STEP 4 — HLS ERROR HANDLING
==================================================

Improve HLS.js handling.

Handle:

- network errors
- media errors
- fatal errors
- failed initialization

Attempt recovery only when appropriate.

Prevent infinite recovery loops.

If recovery fails, display:

"Unable to play this stream."

Do not expose technical stack traces to users.

==================================================
STEP 5 — DOWNLOAD FEATURE
==================================================

Implement an optional download feature.

IMPORTANT:

Do not assume every source is downloadable.

First inspect the existing database.

If an existing field can safely represent download permission, reuse it.

Only create a migration if absolutely necessary.

If migration is required, create the smallest possible migration.

Conceptually:

download_enabled = true/false

Download must only work when:

- source is active
- source belongs to current media
- content is published
- episode is published
- downloading is enabled
- source type supports downloading

Embed sources must never expose a download button.

Do NOT create an arbitrary URL proxy.

Never allow:

/download?url=https://attacker.com/file

The download target must come from the validated VideoSource relationship.

==================================================
STEP 6 — WATCH HISTORY
==================================================

Audit WatchHistory.

Ensure:

- progress cannot be negative
- progress cannot exceed duration
- duration must be greater than zero
- completion calculation is consistent
- duplicate history records are prevented
- user isolation is enforced
- user_id always comes from Auth::id()

Continue Watching must:

- only show incomplete items
- preserve episode-specific progress
- preserve movie progress
- link to the correct watch route
- resume from the saved position

Avoid N+1 queries.

==================================================
STEP 7 — SEARCH
==================================================

Improve the existing search system.

Support:

- title search
- slug search if appropriate
- genre filtering
- content type filtering
- sorting
- pagination

VERY IMPORTANT:

Search must NEVER expose unpublished content.

Test direct URL access as well as search results.

==================================================
STEP 8 — SEO
==================================================

Improve public SEO.

For content pages add:

- dynamic title
- meta description
- canonical URL
- Open Graph title
- Open Graph description
- Open Graph image
- Twitter card

Use actual database data.

Do not fabricate ratings, actors, directors or other metadata.

Prevent duplicate canonical URLs.

==================================================
STEP 9 — STRUCTURED DATA
==================================================

Add appropriate JSON-LD structured data to public content pages.

Only use information actually available in the database.

Do not fabricate:

- ratings
- review counts
- actors
- directors
- release dates

Structured data must remain valid JSON.

==================================================
STEP 10 — SECURITY AUDIT
==================================================

Audit the entire application for:

- IDOR
- mass assignment
- CSRF
- XSS
- unsafe HTML
- open redirects
- arbitrary URL access
- unsafe file uploads
- unauthorized admin access
- unauthorized watch history access
- unauthorized favorites
- unauthorized bookmarks
- unauthorized ratings
- unauthorized video source access

Check all Form Requests.

Check route model binding.

Check admin authorization.

Do not weaken Laravel security.

==================================================
STEP 11 — MEDIA UPLOAD SECURITY
==================================================

Review:

- poster
- backdrop
- thumbnail

Ensure:

- MIME validation
- file size validation
- safe storage
- old file cleanup
- safe filenames
- executable files cannot be uploaded

Do not trust original filenames.

Do not expose filesystem paths.

==================================================
STEP 12 — ADMIN DASHBOARD
==================================================

Improve the admin dashboard.

Display efficient aggregate statistics:

- Total Content
- Movies
- Series
- Anime
- Donghua
- Genres
- Seasons
- Episodes
- Video Sources
- Users
- Watch History
- Ratings
- Favorites
- Watchlist

Use efficient database aggregation.

Do NOT load entire tables just to calculate counts.

==================================================
STEP 13 — ADMIN VIDEO SOURCES
==================================================

Improve Video Source management.

Display:

- Server Name
- Source Type
- Active Status
- Priority
- Content/Episode
- Created At

Support:

- create
- edit
- delete
- activate/deactivate
- priority

Ensure validation remains strict.

==================================================
STEP 14 — PERFORMANCE
==================================================

Audit N+1 queries.

Focus on:

- homepage
- catalog
- content detail
- episode lists
- continue watching
- favorites
- watchlist
- history
- admin content
- admin dashboard
- watch page

Use appropriate:

with()
withCount()
withAvg()
withExists()

Do not over-eager-load.

==================================================
STEP 15 — CACHING
==================================================

Identify safe caching opportunities.

Possible candidates:

- genres
- public statistics
- non-user-specific aggregates

NEVER incorrectly cache user-specific data.

Do not allow cached responses to leak:

- favorites
- watch history
- ratings
- bookmarks
- authenticated user information

==================================================
STEP 16 — ERROR PAGES
==================================================

Create/improve branded error pages:

404
403
419
429
500

Requirements:

- NontonKu branding
- Tailwind styling
- dark/light mode
- useful navigation
- no stack traces
- no sensitive information

==================================================
STEP 17 — UI/UX POLISH
==================================================

Review the public interface.

Improve consistency for:

- buttons
- cards
- forms
- alerts
- empty states
- loading states
- error states
- pagination
- mobile layout
- dark/light mode

Do not redesign the entire application.

Keep the current NontonKu visual identity.

==================================================
STEP 18 — TESTS
==================================================

Create tests only for functionality not already covered.

Recommended:

tests/Feature/User/DownloadTest.php
tests/Feature/User/StreamingSourceTest.php
tests/Feature/User/WatchHistorySecurityTest.php
tests/Feature/SearchTest.php
tests/Feature/SeoTest.php
tests/Feature/SecurityTest.php
tests/Feature/Admin/VideoSourcePriorityTest.php
tests/Feature/Admin/MediaSecurityTest.php

Test:

AUTHORIZATION

- guests cannot access protected features
- normal users cannot access admin
- users cannot access another user's history
- users cannot access another user's favorites
- users cannot access another user's bookmarks

STREAMING

- MP4 works
- HLS works
- Embed works
- inactive source is blocked
- unrelated source is blocked
- unpublished content is blocked
- unpublished episode is blocked

DOWNLOAD

- disabled downloads are blocked
- embed downloads are blocked
- inactive sources are blocked
- unpublished content is blocked
- unrelated sources are blocked

SEARCH

- unpublished content never appears
- pagination works
- filters work

SECURITY

- IDOR attempts fail
- malicious source IDs fail safely
- mass assignment is prevented
- malicious upload MIME types are rejected
- oversized uploads are rejected
- unauthorized admin access fails

SEO

- title exists
- description exists
- canonical exists
- Open Graph metadata exists
- JSON-LD is valid

==================================================
STEP 19 — REGRESSION TEST
==================================================

Before implementation run:

php artisan test

Current baseline:

108 tests
249 assertions

After implementation run:

php artisan optimize:clear
php artisan route:list
php artisan test

The existing 108 tests must continue passing.

Final result must have:

0 failing tests
0 broken routes
0 authorization regressions

==================================================
STEP 20 — FINAL REPORT
==================================================

After completing Phase 9, provide a completion report containing:

1. Files created
2. Files modified
3. Migrations created
4. Routes added
5. Controllers created/modified
6. Services created/modified
7. Form Requests created/modified
8. Policies created/modified
9. Views created/modified
10. Security improvements
11. Streaming improvements
12. Download implementation
13. SEO improvements
14. Performance improvements
15. UI improvements
16. Tests added
17. Final test result
18. Remaining TODOs or limitations

Include the exact final test summary:

Tests: X passed
Assertions: X

==================================================
CRITICAL
==================================================

Implement Phase 9 incrementally.

After each major feature:

1. Run the relevant tests.
2. Fix failures.
3. Continue.

Do NOT move forward while existing functionality is broken.

Most importantly:

DO NOT assume a feature is missing before inspecting the codebase.

Reuse existing Phase 1–8 architecture whenever possible.

The final NontonKu application must remain fully compatible with all existing Phase 1–8 functionality.
