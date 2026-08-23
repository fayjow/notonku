# NontonKu — Implementation Roadmap

This roadmap defines the sequential phases for building NontonKu.

## Phase 0: Planning (Current)
- **Objectives:** Define architecture, database schema, design system, and project rules.
- **Tasks:** Generate `PROJECT.md`, `ARCHITECTURE.md`, `DATABASE.md`, `UI.md`, `STREAMING.md`, `SECURITY.md`, `SEO.md`, `ROADMAP.md`.
- **Dependencies:** None.
- **Acceptance Criteria:** All documentation files created, reviewed, and approved.

## Phase 1: Laravel Foundation
- **Objectives:** Setup Laravel, configure environment, establish Git repository.
- **Tasks:** Install Laravel, setup Vite/Tailwind, configure base models, setup basic routing structure.
- **Dependencies:** Phase 0.
- **Acceptance Criteria:** A fresh Laravel installation displaying a basic Tailwind-styled welcome page.

## Phase 2: Database
- **Objectives:** Implement the schema defined in `DATABASE.md`.
- **Tasks:** Create all migrations, Models, and basic Eloquent relationships. Create model factories and seeders for testing.
- **Dependencies:** Phase 1.
- **Acceptance Criteria:** `php artisan migrate:fresh --seed` runs successfully without errors; relationships function properly in Tinker.

## Phase 3: Authentication
- **Objectives:** Secure the application and prepare user/admin separation.
- **Tasks:** Setup Laravel auth (Breeze/custom), implement `CheckAdmin` middleware, setup Login/Register pages.
- **Dependencies:** Phase 2.
- **Acceptance Criteria:** Users can register/login; admins can access a protected `/admin` route; guests are redirected appropriately.

## Phase 4: Design System
- **Objectives:** Build the core UI components (`x-button`, `x-card`, `x-form`, etc.).
- **Tasks:** Implement Tailwind tokens, create Blade components, ensure Light/Dark mode functionality.
- **Dependencies:** Phase 1.
- **Acceptance Criteria:** A "UI Kit" test page rendering all core components perfectly in both color themes.

## Phase 5: Public Frontend (Browse)
- **Objectives:** Build the homepage, search, and category browsing.
- **Tasks:** Create `x-content-card`, implement Homepage layout, build Search controller and results page with pagination.
- **Dependencies:** Phase 4, Phase 2.
- **Acceptance Criteria:** Users can view trending content, search by title, and filter by genre.

## Phase 6: Content Detail
- **Objectives:** Build the detail page for Movies, Series, Anime, and Donghua.
- **Tasks:** Render metadata, display Season/Episode list for episodic content, implement dynamic SEO meta tags.
- **Dependencies:** Phase 5.
- **Acceptance Criteria:** Navigating to a content slug displays full details, correct episodes, and proper Open Graph tags.

## Phase 7: Streaming
- **Objectives:** Implement the provider-agnostic streaming architecture.
- **Tasks:** Build `StreamingService`, create basic `Embed` and `Direct` provider adapters, build the Watch Page UI (`/watch`).
- **Dependencies:** Phase 6.
- **Acceptance Criteria:** Users can click "Watch", select a server, and the video player renders the correct source.

## Phase 8: Download
- **Objectives:** Implement download source management.
- **Tasks:** Build `DownloadService`, display available download links/qualities on the content and watch pages.
- **Dependencies:** Phase 7.
- **Acceptance Criteria:** Users can see and click valid download links for specific episodes/movies.

## Phase 9: User Features
- **Objectives:** Implement authenticated features.
- **Tasks:** Build Favorites, Watchlist, and Watch History (progress tracking).
- **Dependencies:** Phase 3, Phase 7.
- **Acceptance Criteria:** Logged-in users can favorite a movie, add to watchlist, and their watch progress is saved.

## Phase 10: Admin CMS
- **Objectives:** Build the backend management interface.
- **Tasks:** Create CRUD interfaces for Content, Seasons, Episodes, and Media Sources.
- **Dependencies:** Phase 4.
- **Acceptance Criteria:** Admins can fully manage the platform's catalog and media links without touching the database directly.

## Phase 11: SEO
- **Objectives:** Finalize all technical SEO requirements.
- **Tasks:** Implement dynamic XML sitemap, robots.txt, and complete JSON-LD structured data on all public views.
- **Dependencies:** Phase 6.
- **Acceptance Criteria:** Google Rich Results Test validates the JSON-LD without errors; sitemap is valid.

## Phase 12: PWA (Optional/Future)
- **Objectives:** Make the site installable as a Progressive Web App.
- **Tasks:** Add Web App Manifest, Service Worker for offline fallback/caching.

## Phase 13: Security
- **Objectives:** Final security sweep.
- **Tasks:** Implement rate limiting, secure headers, thorough Form Request validation audits.
- **Dependencies:** Phase 10.
- **Acceptance Criteria:** Security audit passes; no CSRF/XSS vulnerabilities identified.

## Phase 14: Performance
- **Objectives:** Optimize application speed.
- **Tasks:** Implement Redis caching for heavy queries, optimize database indexes, eager-load N+1 fixes, optimize images.
- **Dependencies:** Phase 10.
- **Acceptance Criteria:** Lighthouse score > 90 for performance; minimal database queries per page load.

## Phase 15: Testing
- **Objectives:** Ensure stability.
- **Tasks:** Write Pest PHP Feature tests for core flows (Auth, Watching, Admin CRUD).
- **Dependencies:** All previous phases.
- **Acceptance Criteria:** Critical paths have automated test coverage passing successfully.

## Phase 16: Production
- **Objectives:** Deploy the application.
- **Tasks:** Server provisioning, deployment script setup (e.g., Envoyer/Deployer), SSL configuration.
- **Acceptance Criteria:** NontonKu is live and accessible to the public.
