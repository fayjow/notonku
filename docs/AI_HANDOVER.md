# NontonKu - AI Handover / Project State Recap

**Last Updated:** Phase 10 Completed (Pre-Phase 11)

This document is designed to provide any AI Agent with a complete, holistic understanding of the NontonKu project. It summarizes the architecture, models, controllers, views, database schema, and implemented features so you do not need to rediscover the system from scratch.

---

## 1. Project Overview & Identity

- **Name:** NontonKu
- **Type:** Media Streaming Platform (Movies, Series, Anime, Donghua).
- **Tech Stack:** Laravel 12, PHP 8.4+, MySQL, Blade Templates, Tailwind CSS (v3), Alpine.js, Vite.
- **Key Constraints:**
    - Do NOT use React/Vue/Inertia/Livewire. Keep it native Blade + Alpine.js.
    - Controllers must remain thin. Use Services for business logic.
    - Do not create duplicate tables for media types. All media uses the polymorphic-like Enum `Content` system.
    - 100% Test pass rate required (Pest PHP).

---

## 2. Core Architecture & Models

The content architecture avoids creating separate tables for Movies, Series, etc. Instead, it relies on a central `Content` table using a `type` enum.

### 2.1 Media Models

- **`Content`**: The core entity.
    - Fields: `id`, `tmdb_id`, `title`, `slug`, `description`, `type` (enum: movie, series, anime, donghua), `release_date`, `status` (enum: upcoming, ongoing, completed), `poster_path`, `backdrop_path`, `trailer_url`, `is_published`, `rating`, `view_count`.
    - Relationships: `seasons()`, `episodes()` (HasManyThrough), `genres()` (BelongsToMany), `videoSources()` (MorphMany), `favorites()`, `bookmarks()`, `ratings()`, `watchHistories()`.
- **`Season`**: Belongs to `Content`. Used for Series/Anime/Donghua.
    - Fields: `id`, `content_id`, `season_number`, `title`, `description`.
    - Relationships: `content()`, `episodes()`.
- **`Episode`**: Belongs to `Season`.
    - Fields: `id`, `season_id`, `episode_number`, `title`, `description`, `thumbnail_path`, `duration_minutes`, `is_published`.
    - Relationships: `season()`, `videoSources()` (MorphMany), `watchHistories()`.
- **`Genre`**: BelongsToMany `Content` (pivot: `content_genre`).
    - Fields: `id`, `name`, `slug`, `description`.
- **`VideoSource`**: Polymorphic (MorphTo). Attached directly to `Content` (for movies) or `Episode` (for series).
    - Fields: `sourceable_type`, `sourceable_id`, `provider` (enum: mp4, hls, embed), `url`, `quality`, `server_name`, `language`, `priority`, `is_active`, `is_downloadable`, `supports_autoplay` (boolean, for embeds).

### 2.2 User Engagement Models

- **`User`**: Native Laravel User + `is_admin` boolean flag.
- **`Favorite`**: Tracks user favorite contents. (`user_id`, `content_id`).
- **`Bookmark`**: Used for Watchlist functionality. (`user_id`, `content_id`).
- **`Rating`**: Tracks user 1-5 star ratings. (`user_id`, `content_id`, `rating`).
- **`WatchHistory`**: Tracks watch progress for "Continue Watching".
    - Fields: `user_id`, `content_id` (nullable), `episode_id` (nullable), `progress_seconds`, `duration_seconds`, `is_completed`, `last_watched_at`.

---

## 3. Controllers & Routing

### 3.1 Public Routes (Guest & Auth)

- `HomeController@index` (`/`): Homepage showing Featured, Latest, Popular, and "Because You Watched" (Continue Watching).
- `MovieController@show` (`/movies/{slug}`)
- `SeriesController@show` (`/series/{slug}`)
- `AnimeController@show` (`/anime/{slug}`)
- `DonghuaController@show` (`/donghua/{slug}`)
- `WatchController@watch` (`/watch/{type}/{slug}/{episode?}`): Video player page.
- `WatchController@download` (`/download/{source_id}`): Secure download proxy.
- `SearchController@index` (`/search`): Advanced filtering (keyword, type, genre, status, sorting).
- `GenreController@index` (`/genres`), `GenreController@show` (`/genres/{slug}`)

### 3.2 User Panel Routes (Requires Auth)

- `ProfileController@index` (`/profile`): User dashboard / stats.
- `FavoriteController@index`, `@toggle`: Manage favorites.
- `WatchlistController@index`, `@toggle`: Manage watchlist.
- `WatchHistoryController@index`, `@store`, `@destroy`: Manage history / update video progress via AJAX.
- `RatingController@index`, `@store`: Manage ratings.

### 3.3 Admin Routes (Requires Auth + `CheckAdmin` Middleware)

Prefix: `/admin`

- `AdminController@dashboard`: Admin dashboard stats.
- Resource Controllers for CRUD operations:
    - `Admin\ContentController`
    - `Admin\SeasonController`
    - `Admin\EpisodeController`
    - `Admin\GenreController`
    - `Admin\VideoSourceController`

---

## 4. Services & Logic

- **`RecommendationService`**: Handles fetching popular content (`popular()`), generating "Because You Watched" recommendations based on `WatchHistory`, and fetching related content for the details page based on shared genres.
- **Secure File Uploads**: Posters and thumbnails are stored using Laravel's Storage facade (usually `public` disk), accessible via `/storage/...`.
- **Video Player (`watch.blade.php`)**: Custom HTML5 Player built with Alpine.js.
    - `mp4` uses native HTML5 `<video>`.
    - `hls` uses `hls.js` library via CDN.
    - `embed` uses `<iframe>` (conditionally handles autoplay via `supports_autoplay`).
    - Syncs progress back to `WatchHistoryController@store` every 10 seconds via Fetch API.

---

## 5. Views & Frontend

### 5.1 Layouts

- `layouts.public`: For guest/public viewing. Includes header (navigation, search bar, dark mode toggle) and footer.
- `layouts.admin`: Admin dashboard layout with sidebar.
- `layouts.guest`: Auth forms layout.

### 5.2 Key Public Views

- `public.home`: Highly dynamic homepage.
- `public.show`: Media details, genre tags, seasons/episodes list, trailer modal, related content.
- `public.watch`: The Alpine.js video player + episode sidebar.
- `public.search`: Filter forms + grid layout.
- `public.genres.show`: Filtered by genre.
- `user.*`: Profile panels for history, favorites, and watchlist.

### 5.3 Styling (Tailwind)

- Full Dark / Light mode support via Tailwind's `dark:` classes.
- Standardized components (Cards, Badges, Buttons, Modals) using Blade components (e.g., `<x-content-card>`, `<x-section-heading>`).

---

## 6. Testing & Quality

- **Pest PHP**: The testing framework.
- **Pass Rate**: 127/127 tests passing.
- **Coverage Types**: Feature tests for Admin Routes, Auth, Public UI, User Interactions (WatchHistory, Ratings, Favorites), and Search/Genre Filtering.

---

## 7. Current Project Phase (Next Steps)

The project has successfully finished **Phase 10**.
The next target is **Phase 11: Production Infrastructure, Observability & Advanced Streaming**.
When starting new tasks, refer to this documentation and ensure you do NOT break the existing 127 tests or rewrite working architecture unnecessarily.
