# Phase 11 Implementation Plan: Production Infrastructure, Observability & Advanced Streaming

## Goal

Phase 11 aims to take NontonKu from a production-ready Laravel application into a truly deployment-ready streaming platform.

Phase 1-10 are considered complete and must NOT be broken.

Current baseline:

- Laravel application: NontonKu
- Phase 10 completed
- 127 automated tests currently passing
- Authentication and authorization implemented
- Admin Content Management implemented
- Favorites / Watchlist / Watch History implemented
- Continue Watching implemented
- Ratings implemented
- Custom HTML5 video player implemented
- MP4 / HLS / Embed streaming supported
- Secure video source selection implemented
- Secure downloading implemented
- Search and advanced filtering implemented
- Genre discovery implemented
- RecommendationService implemented
- SEO / Open Graph / JSON-LD implemented
- Dark / Light mode implemented
- Custom error pages implemented
- Security audit completed
- N+1 issues reviewed and optimized

The primary objective of Phase 11 is:

1. Production deployment readiness
2. Observability and logging
3. Streaming reliability
4. Database and application performance
5. Queue and scheduled task architecture
6. Backup and recovery readiness
7. Security hardening
8. Admin operational tools
9. Automated health checks
10. Comprehensive final regression testing

---

# IMPORTANT RULES

Before modifying anything:

1. Inspect the existing NontonKu codebase.
2. Read existing migrations, models, controllers, services, routes, middleware, requests, views, and tests.
3. Reuse existing architecture whenever possible.
4. DO NOT create duplicate functionality.
5. DO NOT introduce unnecessary migrations.
6. DO NOT replace existing working implementations unless there is a clear technical reason.
7. Preserve all existing routes unless a backward-compatible improvement is necessary.
8. Preserve the existing public UI style.
9. Preserve dark/light mode.
10. Preserve all existing security protections.
11. Never trust IDs supplied by users without verifying ownership/relationship.
12. Never expose stack traces, filesystem paths, credentials, or internal exceptions to users.
13. Never hardcode secrets, API keys, passwords, or production credentials.
14. Do not introduce a heavy third-party package unless absolutely necessary.
15. Keep controllers thin and move reusable business logic into Services where appropriate.

CRITICAL:

Run the existing test suite before making changes:

php artisan test

The existing 127 tests must remain passing after Phase 11.

---

# 1. Production Environment Audit

Inspect and improve:

- .env.example
- config/app.php
- config/database.php
- config/cache.php
- config/filesystems.php
- config/queue.php
- config/logging.php
- config/session.php
- config/mail.php

Ensure production-safe configuration.

Review:

- APP_ENV
- APP_DEBUG
- APP_URL
- LOG_CHANNEL
- CACHE_STORE
- SESSION_DRIVER
- QUEUE_CONNECTION
- FILESYSTEM_DISK
- database configuration

Do NOT put real production credentials into source control.

Improve `.env.example` with clearly documented required variables.

---

# 2. Health Check System

Create a production health endpoint:

GET /health

It should verify:

- Application is running
- Database connection works
- Cache system works
- Storage is writable when applicable

Return a simple JSON response.

Example:

{
"status": "ok",
"database": "ok",
"cache": "ok",
"storage": "ok"
}

If a dependency fails:

- Return HTTP 503
- Do not expose internal exception details

Create:

app/Http/Controllers/HealthController.php

Add automated tests:

tests/Feature/HealthCheckTest.php

Test:

- Healthy application
- Database failure handling if practical
- Unauthorized access should NOT expose sensitive information

---

# 3. Application Logging & Error Handling

Review Laravel exception handling.

Ensure production errors:

- Are logged
- Do not expose stack traces
- Do not expose SQL queries
- Do not expose filesystem paths
- Do not expose credentials

Add useful contextual logging where appropriate.

Examples:

- Failed video source resolution
- Failed download attempt
- Failed watch progress update
- Failed media upload
- Admin destructive operations

Do NOT log:

- Passwords
- Session tokens
- CSRF tokens
- Authorization headers
- Full sensitive request payloads

Use structured/contextual logs where appropriate.

---

# 4. Video Streaming Reliability

Improve:

resources/views/public/watch.blade.php

and relevant WatchController logic.

For MP4:

- Handle `video.error`
- Handle loading state
- Handle buffering
- Display friendly playback errors
- Prevent progress saving when duration is invalid

For HLS:

- Improve HLS.js error handling
- Handle network errors
- Handle media errors
- Handle fatal errors
- Attempt recovery when safe
- Avoid infinite retry loops

For Embed:

- Keep iframe sandbox security
- Validate URLs
- Keep progress tracking disabled
- Display fallback UI if iframe cannot be loaded

The player must never display raw JavaScript exceptions to users.

---

# 5. Streaming Source Health & Fallback

Improve the existing VideoSource architecture.

Create a service if appropriate:

app/Services/VideoSourceService.php

Responsibilities:

- Resolve active sources
- Apply priority ordering
- Validate source type
- Select fallback source
- Prevent inactive sources
- Prevent IDOR
- Prevent sources belonging to another content/episode

Optional:

Track source failures in logs.

DO NOT add a source health database system unless absolutely necessary.

The existing database structure should be reused.

---

# 6. Download Security Review

Review:

DownloadController
VideoSource model
download routes
download permissions

Ensure:

- Only active sources can be downloaded
- Only `is_downloadable = true` sources can be downloaded
- Embed sources cannot be downloaded
- HLS sources cannot be treated as direct downloads
- Source belongs to requested Content/Episode
- Unpublished content cannot be downloaded
- User authorization is enforced
- IDOR is impossible

Test malicious requests such as:

/download/999999

/download/{valid-source-from-other-content}

/download?source_id=...

Do not allow users to manipulate IDs to access unrelated files.

---

# 7. Database Performance Audit

Perform a complete database performance audit.

Inspect:

- contents
- genres
- content_genre
- seasons
- episodes
- video_sources
- users
- favorites
- bookmarks/watchlist
- ratings
- watch_histories

Check for:

- Missing indexes
- Duplicate indexes
- Foreign key indexes
- Frequently queried columns

Pay particular attention to:

- slug
- type
- is_published
- status
- user_id
- content_id
- episode_id
- sourceable_type
- sourceable_id

Only create migrations when an index is genuinely beneficial.

Do NOT blindly add indexes.

---

# 8. Queue Architecture

Prepare the application for asynchronous jobs.

Review whether the following should be queued:

- Image processing
- Large media operations
- Recommendation recalculation
- Cleanup tasks
- Analytics aggregation

Do NOT unnecessarily queue simple operations.

If queue jobs are introduced:

Create appropriate Jobs under:

app/Jobs/

Use:

- ShouldQueue
- retry/backoff strategy
- failed job handling

Do not introduce Redis unless the application actually requires it.

The default database queue should remain acceptable for development.

---

# 9. Scheduled Maintenance Tasks

Create scheduled maintenance tasks where useful.

Examples:

- Remove orphaned temporary files
- Cleanup expired sessions if applicable
- Cleanup failed/obsolete records if safe
- Recalculate cached statistics if needed

Use Laravel Scheduler.

Ensure scheduled tasks are:

- Idempotent
- Safe to run multiple times
- Efficient
- Logged

Do not create destructive automatic cleanup without strong safeguards.

---

# 10. Admin Operational Dashboard

Improve:

resources/views/admin/dashboard.blade.php

Add useful operational information:

### Content

- Total Movies
- Total Series
- Total Anime
- Total Donghua
- Published Content
- Unpublished Content

### Media

- Total Episodes
- Total Video Sources
- Active Video Sources
- Downloadable Sources

### Users

- Total Users
- Recent Registrations

### Activity

- Recently Added Content
- Recently Added Episodes
- Recent Ratings
- Recent Watch History activity

Avoid loading entire collections.

Use:

- count()
- latest()
- limit()
- withCount()
- withAvg()

where appropriate.

---

# 11. Admin Media Diagnostics

Add an optional admin page:

/admin/media/diagnostics

The page should help administrators identify:

- Content without poster
- Content without backdrop
- Episodes without thumbnail
- Published content without video source
- Published episodes without video source
- Inactive video sources
- Invalid source configuration

This page should be read-only.

Do NOT expose sensitive filesystem information.

Add:

tests/Feature/Admin/MediaDiagnosticsTest.php

---

# 12. Backup & Recovery Readiness

Do NOT automatically upload backups anywhere.

Instead:

Create documentation:

docs/PRODUCTION.md
docs/BACKUP.md

Document:

- Database backup strategy
- Storage backup strategy
- `.env` secret handling
- Laravel deployment process
- Rollback strategy
- Migration strategy
- Storage symlink requirements
- Queue worker requirements
- Scheduler requirements
- Cache clearing

The documentation must be practical for deployment on a Linux server.

---

# 13. Deployment Optimization

Prepare the application for production deployment.

Verify compatibility with:

- Nginx
- PHP-FPM
- MySQL/MariaDB
- Laravel storage
- Queue worker
- Cron scheduler

Document commands such as:

php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

DO NOT execute destructive production commands automatically.

Create:

docs/DEPLOYMENT.md

Include:

1. Server requirements
2. PHP extensions
3. Database setup
4. Environment configuration
5. Composer installation
6. Storage configuration
7. Migration
8. Cache configuration
9. Queue configuration
10. Scheduler configuration
11. Nginx configuration overview
12. SSL recommendation
13. Backup
14. Rollback
15. Health check

---

# 14. Security Hardening

Perform another complete security audit.

Review:

- Authentication
- Authorization
- Admin middleware
- CSRF
- XSS
- IDOR
- Mass assignment
- File upload validation
- Download access
- Video source access
- Rate limiting
- Session handling
- Password handling
- Error responses

Pay particular attention to:

- route model binding
- user-owned records
- polymorphic relationships
- source_id
- content_id
- episode_id
- download endpoints

Never trust relationships supplied by request parameters.

---

# 15. Rate Limiting

Review rate limits for:

- Login
- Registration
- Password reset
- Search
- Watch progress
- Downloads
- Rating
- Favorite
- Watchlist

Do not apply aggressive rate limits that break normal UX.

For watch progress specifically:

Avoid allowing a client to spam hundreds of requests per second.

---

# 16. API / AJAX Response Consistency

Review all existing fetch/AJAX endpoints.

Ensure consistent JSON responses.

Success example:

{
"success": true,
"message": "Operation successful"
}

Error example:

{
"success": false,
"message": "Unable to complete operation"
}

Do not expose internal exceptions.

Ensure HTTP status codes are appropriate:

200
201
204
400
401
403
404
422
429
500

---

# 17. Frontend Production Audit

Review all public and admin Blade views.

Check:

- Broken links
- Missing images
- Broken routes
- Console errors
- Missing alt attributes
- Missing ARIA labels
- Keyboard navigation
- Focus states
- Responsive layouts
- Dark mode
- Mobile layouts

Particularly inspect:

- Home
- Search
- Genre
- Show
- Watch
- Favorites
- Watchlist
- History
- Profile
- Admin dashboard
- Admin CRUD pages

Do not redesign the entire application.

Only polish and fix actual problems.

---

# 18. SEO Final Audit

Review:

- `<title>`
- meta description
- canonical URLs
- Open Graph
- Twitter/X cards
- JSON-LD
- robots.txt
- sitemap

Create or improve:

GET /sitemap.xml

The sitemap should include only public, published content.

Do NOT include:

- Admin pages
- User profile pages
- Favorites
- Watchlist
- History
- Unpublished content

Add appropriate robots directives.

---

# 19. Automated Tests

Create or improve:

tests/Feature/HealthCheckTest.php
tests/Feature/StreamingReliabilityTest.php
tests/Feature/DownloadSecurityTest.php
tests/Feature/Admin/MediaDiagnosticsTest.php
tests/Feature/SitemapTest.php
tests/Feature/ProductionSecurityTest.php

Expand existing tests where appropriate.

Test:

### Health

- Health endpoint
- Failure handling

### Streaming

- MP4
- HLS
- Embed
- Invalid source
- Inactive source
- Wrong content source
- Unpublished content

### Download

- Downloadable MP4
- Non-downloadable source
- HLS blocked
- Embed blocked
- Wrong content blocked
- Unpublished content blocked

### Security

- IDOR
- CSRF
- XSS
- Mass assignment
- Unauthorized admin access

### Sitemap

- Published content included
- Unpublished content excluded
- Admin URLs excluded
- User URLs excluded

---

# 20. Final Regression Testing

After implementation:

Run:

php artisan optimize:clear

php artisan route:list

php artisan test

The final test suite MUST have:

- 127 existing tests still passing
- All new Phase 11 tests passing
- ZERO failures
- ZERO unexpected skipped tests

If a test fails:

1. Identify the root cause.
2. Fix the implementation.
3. Re-run the affected test.
4. Re-run the full suite.

Do NOT modify tests merely to make them pass unless the original test is genuinely incorrect.

---

# 21. Final Code Quality Audit

Before declaring Phase 11 complete:

Check for:

- Dead code
- Duplicate logic
- Unused imports
- Debug statements
- `dd()`
- `dump()`
- `var_dump()`
- Hardcoded credentials
- Hardcoded filesystem paths
- TODOs that affect production
- N+1 queries
- Unsafe raw SQL
- Unsafe HTML output
- Missing authorization
- Missing validation

Run:

php artisan optimize:clear
php artisan test

---

# Expected Final Result

At the end of Phase 11, NontonKu should be:

- Production deployment ready
- Secure against common web vulnerabilities
- Secure against IDOR
- Secure against unsafe downloads
- Stable under streaming errors
- Observable through logs
- Equipped with health checks
- Performance optimized
- Database-index optimized where justified
- Ready for queues and scheduled tasks
- Equipped with admin diagnostics
- SEO complete
- Backup/deployment documented
- Mobile responsive
- Accessible
- Dark/light mode compatible
- Fully tested

MOST IMPORTANT:

Do not claim Phase 11 is complete until:

php artisan test

returns ZERO failures.

Provide a final completion report containing:

1. Files created
2. Files modified
3. Migrations added
4. Routes added
5. Controllers added
6. Services added
7. Jobs added
8. Scheduler changes
9. Security improvements
10. Performance improvements
11. Documentation added
12. Tests added
13. Exact final test count
14. Exact assertion count
15. Any remaining TODOs or limitations

Do not hide known limitations.
