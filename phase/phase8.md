# NontonKu — Phase 8 Implementation

## Admin Panel Polish, Media Upload & Advanced Streaming Support

You are continuing development of the existing Laravel NontonKu project.

IMPORTANT:

- Do NOT rebuild the project.
- Do NOT change the existing architecture unnecessarily.
- Do NOT remove or weaken existing authorization/security.
- Preserve all functionality from Phase 1 through Phase 7.
- Treat the current codebase as the source of truth.
- Before modifying anything, inspect the existing models, migrations, routes, controllers, Blade components, services, policies, Form Requests, and tests.
- Reuse existing relationships and database columns whenever possible.
- Do not create duplicate tables or duplicate functionality.
- Follow existing Laravel conventions and coding style.

CURRENT STATUS:

Phase 1-7 are completed.

Current automated test status:

    Tests: 91 passed
    Assertions: 205

The project already has:

- Authentication
- Email verification
- Password reset
- Admin authorization
- Content management architecture
- Genres
- Seasons
- Episodes
- Video Sources
- Favorites
- Watchlist / Episode Bookmarks
- Watch History
- Ratings
- Continue Watching
- Custom HTML5 video player
- Episode navigation
- Auto-next episode
- Dark/light mode
- Public catalog
- Search
- Pagination
- Admin dashboard
- Admin CRUD architecture

Existing important structures include:

- Content
- Genre
- Season
- Episode
- VideoSource
- User
- WatchHistory
- Rating
- Favorites
- Episode bookmarks
- Polymorphic video_sources relationship

Existing video player routes include:

    GET /watch/movie/{content}
    GET /watch/series/{content}/{episode}
    GET /watch/anime/{content}/{episode}
    GET /watch/donghua/{content}/{episode}
    POST /watch/{content}/progress

Existing tests must continue passing.

============================================================
PHASE 8 OBJECTIVES
============================================================

Phase 8 should focus on two major areas:

1. ADMIN MEDIA MANAGEMENT
2. ADVANCED VIDEO STREAMING SUPPORT

Do NOT introduce unrelated features.

---

1. ADMIN IMAGE UPLOAD SYSTEM

---

Currently poster_path, backdrop_path, and thumbnail_path are used as path/string fields.

Implement proper Laravel file uploads using:

    storage/app/public

and the public storage symlink.

Before implementation:

- Inspect existing migrations.
- Inspect Content and Episode models.
- Inspect existing image accessors.
- Inspect current Admin Content/Episode controllers.
- Inspect existing Blade forms.

Do not create new image columns if the existing columns can already store the paths.

Required functionality:

Content:

- poster upload
- backdrop upload

Episode:

- thumbnail upload

Requirements:

- Validate MIME type.
- Validate file size.
- Accept common image formats such as:
    - jpg
    - jpeg
    - png
    - webp

Use Laravel Storage APIs.

Example concept:

    Storage::disk('public')->store(...)

Do NOT hardcode filesystem paths.

Store only the relative storage path in the database.

Example:

    posters/example.webp

Then expose the image through Laravel's public storage mechanism.

Ensure:

    php artisan storage:link

is documented/required.

---

2. IMAGE REPLACEMENT & CLEANUP

---

When an administrator replaces an existing image:

- Store the new image.
- Remove the old file if it belongs to the application's public storage.
- Update the database path.

When an administrator deletes content/episode:

- Consider whether associated images should also be removed.
- Do not delete arbitrary external URLs.
- Only delete files that are actually managed by the application's storage disk.

Avoid deleting files outside the intended storage location.

Add automated tests for:

- successful upload
- invalid file rejection
- replacing an existing image
- deleting managed image
- external URL/path safety

---

3. ADMIN MEDIA UI

---

Improve admin Content and Episode forms.

Instead of only:

    poster_path
    backdrop_path
    thumbnail_path

provide:

- file picker
- current image preview
- filename display
- optional remove-image checkbox/button

Example UI:

    Current Poster
    [image preview]

    Upload New Poster
    [Choose File]

Make the interface responsive.

It must work in:

- light mode
- dark mode
- desktop
- tablet
- mobile

Use the existing Tailwind style language.

Do not introduce a heavy UI framework.

---

4. VIDEO SOURCE SYSTEM

---

Current limitation:

The video player assumes the source is a raw .mp4 URL.

Improve the VideoSource architecture so it can support multiple source types.

Possible source types:

    mp4
    hls
    embed

IMPORTANT:

First inspect the current VideoSource database schema.

If an existing field can safely represent the source type, reuse it.

Only create a migration if absolutely necessary.

Do not duplicate an existing field.

---

5. VIDEO SOURCE ADMIN UI

---

Improve the Video Source management UI.

Admin should be able to specify:

- Server name
- Source type
- URL
- Active/inactive state

Example:

    Server Name:
    VidCloud

    Type:
    MP4 / HLS / Embed

    URL:
    https://example.com/video.m3u8

    Active:
    Yes

Validation must depend on source type where appropriate.

Examples:

MP4:

- Must be a valid URL.

HLS:

- Must be a valid URL.
- Prefer .m3u8 but do not blindly reject valid URLs if provider URLs do not expose extensions.

Embed:

- Must be a valid URL.

Do not implement unsafe arbitrary HTML injection.

---

6. ADVANCED VIDEO PLAYER

---

Upgrade:

    resources/views/public/watch.blade.php

The player must support:

A. MP4

Use native HTML5 video.

B. HLS

Support .m3u8 streams.

Preferred architecture:

- Use native HLS where supported.
- Use hls.js as fallback where necessary.

Do not load unnecessary libraries on pages that do not need HLS.

C. Embed

If source type is embed:

- Render the provider using a controlled iframe.
- Do NOT inject arbitrary HTML.
- Apply reasonable iframe security attributes.
- Do not allow javascript URLs.

The player should dynamically select the correct renderer based on source type.

---

7. VIDEO SOURCE FALLBACK

---

If multiple active video sources exist:

- Prefer the first available source.
- Provide a source/server selector when multiple sources exist.

Example:

    Server:
    [Server 1] [Server 2] [Server 3]

Switching source should not break:

- authentication
- watch history
- episode navigation
- dark mode

Do not expose inactive sources to normal users.

---

8. STREAMING ERROR HANDLING

---

Create proper UI states for:

- source unavailable
- video failed to load
- HLS initialization failure
- embed blocked
- network error
- no active source

Example:

    Unable to play this video.

    Please try another server.

Provide a "Try Another Server" action when alternatives exist.

Do not expose raw exceptions or stack traces to users.

---

9. WATCH HISTORY COMPATIBILITY

---

Preserve the existing WatchHistoryService behavior.

Existing functionality must continue working:

- progress updates
- resume playback
- Continue Watching
- completion at approximately 90%
- episode-specific history
- user isolation

Do NOT rewrite WatchHistoryService unless absolutely necessary.

Ensure switching between video sources does not create duplicate history records.

---

10. PLAYER UX IMPROVEMENTS

---

Preserve the current custom player.

Improve:

- buffering indicator
- playback error state
- source switching
- responsive layout
- mobile controls
- keyboard controls
- fullscreen
- playback speed
- progress indicator
- resume overlay
- next episode countdown

Keyboard shortcuts should remain functional:

    Space = Play/Pause
    F = Fullscreen

Do not remove existing features.

---

11. SECURITY REQUIREMENTS

---

This phase must be security-focused.

Admin media upload:

- Validate uploaded files.
- Never trust original filenames.
- Never execute uploaded files.
- Use Laravel Storage.
- Do not accept arbitrary filesystem paths.

Video sources:

- Validate URLs.
- Never render arbitrary HTML from database fields.
- Never allow javascript: URLs.
- Never inject iframe HTML directly from user input.
- Only expose active sources.
- Preserve admin authorization.

Watch routes:

- Guests remain unauthorized if that is the current architecture.
- Unpublished content remains inaccessible.
- Unpublished episodes remain inaccessible.
- Episode must belong to requested content.
- Users cannot access another user's watch history.

---

12. TESTING

---

Create or update Pest tests.

Add:

    tests/Feature/Admin/MediaUploadTest.php

Test:

- admin can upload poster
- admin can upload backdrop
- admin can upload episode thumbnail
- invalid image rejected
- unauthorized user cannot upload
- old image is cleaned when replaced
- external image paths are not accidentally deleted

Add:

    tests/Feature/Admin/VideoSourceManagementTest.php

Test:

- admin can create MP4 source
- admin can create HLS source
- admin can create embed source
- invalid source rejected
- inactive source is hidden from users
- unauthorized user cannot manage sources

Add:

    tests/Feature/User/AdvancedVideoPlayerTest.php

Test:

- MP4 source renders correctly
- HLS source renders correctly
- embed source renders correctly
- missing source shows graceful empty state
- inactive source is not exposed
- multiple sources can be selected
- unpublished content remains inaccessible
- wrong episode/content relationship remains blocked

Update existing tests only when required by legitimate architecture changes.

---

13. PERFORMANCE

---

Avoid N+1 queries.

Use appropriate:

    with()
    load()
    withCount()

Do not load unnecessary relationships.

Do not query the database repeatedly during video playback.

Watch progress requests should remain lightweight.

Do not create a database request every second.

The existing approximately 10-second progress interval should remain.

---

14. ROUTING

---

Do not replace the existing explicit watch routes.

Preserve:

    watch.movie
    watch.series
    watch.anime
    watch.donghua
    watch.progress

If additional routes are required, follow the existing naming convention.

Run:

    php artisan route:list

and verify no collisions exist.

---

15. DARK/LIGHT MODE

---

Dark/light mode must continue working across:

- Admin dashboard
- Content forms
- Episode forms
- Video source forms
- Media previews
- Video player
- Error states
- Source selector

Do not introduce a separate theme system.

Reuse the existing implementation.

---

16. CODE QUALITY

---

Follow Laravel conventions.

Prefer:

- Form Requests for validation
- Policies/middleware for authorization
- Eloquent relationships
- Storage facade
- thin controllers
- reusable Blade components

Avoid:

- massive controllers
- duplicated validation
- duplicated queries
- raw SQL unless necessary
- inline JavaScript where reusable Alpine components are appropriate
- unnecessary dependencies

---

17. IMPORTANT: DO NOT BREAK EXISTING PHASES

---

After implementation, verify:

Phase 1:
Authentication

Phase 2:
Database relationships

Phase 3:
Authorization

Phase 4:
Public catalog/search

Phase 5:
Favorites
Watchlist
Watch History
Ratings
Continue Watching

Phase 6:
Video player
Resume playback
Episode navigation
Auto-next

Phase 7:
Admin content management
Genres
Seasons
Episodes
Video sources
Admin dashboard

All must remain functional.

---

18. VERIFICATION COMMANDS

---

Run:

    php artisan optimize:clear

Then:

    php artisan route:list

Then:

    php artisan test

The final result must have:

    0 failed tests

Do not simply report that tests passed.

Inspect the actual output.

Also check:

    php artisan storage:link

If the link already exists, do not recreate it unnecessarily.

---

19. MANUAL QA CHECKLIST

---

After automated tests pass, manually verify:

ADMIN:

[ ] Admin dashboard opens
[ ] Normal user cannot access admin
[ ] Content CRUD works
[ ] Genre CRUD works
[ ] Season CRUD works
[ ] Episode CRUD works
[ ] Video Source CRUD works
[ ] Poster upload works
[ ] Backdrop upload works
[ ] Episode thumbnail upload works
[ ] Image preview works
[ ] Image replacement works
[ ] Dark mode works
[ ] Light mode works

VIDEO PLAYER:

[ ] Movie MP4 plays
[ ] Series episode MP4 plays
[ ] Anime episode plays
[ ] Donghua episode plays
[ ] HLS source works
[ ] Embed source works
[ ] Multiple servers can be selected
[ ] Inactive servers are hidden
[ ] Resume playback works
[ ] Watch history updates
[ ] Continue Watching works
[ ] Previous episode works
[ ] Next episode works
[ ] Auto-next works
[ ] Fullscreen works
[ ] Playback speed works
[ ] Mobile layout works
[ ] Dark mode works
[ ] Light mode works
[ ] Error state is graceful

---

20. FINAL REPORT

---

When implementation is complete, provide a structured completion report containing:

1. Files created
2. Files modified
3. Migration changes
4. Routes added/modified
5. Controllers added/modified
6. Models modified
7. Form Requests added/modified
8. Blade views/components added/modified
9. JavaScript/Alpine changes
10. Storage changes
11. Security changes
12. Video streaming changes
13. Tests added/modified
14. Exact test result
15. Exact assertion count
16. Any remaining TODOs
17. Any known limitations

IMPORTANT:

Do not claim a feature is implemented unless you actually verified it in the codebase or through tests.

If something cannot be implemented because the current database schema or architecture does not support it safely, stop and explain the exact limitation instead of making a destructive architectural change.

The priority order is:

1. Preserve existing functionality
2. Security
3. Correctness
4. Test coverage
5. Performance
6. UX
7. Visual polish

Begin by inspecting the current Phase 1-7 implementation and database schema before making changes.
