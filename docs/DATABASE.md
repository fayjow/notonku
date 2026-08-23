# NontonKu — Database Design

## Overview
The database is designed with `contents` as the primary entity. Separate primary tables for movies, series, anime, and donghua are avoided in favor of a single unified table with a `type` discriminator. 

## Tables

### 1. `users`
- `id` (PK, unsigned bigint)
- `name` (string)
- `email` (string, unique)
- `email_verified_at` (timestamp, nullable)
- `password` (string)
- `role` (string, default 'user') - Roles (user, admin, editor, moderator)
- `remember_token` (string, nullable)
- `timestamps`
- Index: (`role`)

### 2. `contents`
- `id` (PK, unsigned bigint)
- `type` (string) - Backed by PHP Enum: 'movie', 'series', 'anime', 'donghua'
- `title` (string, index)
- `slug` (string, unique)
- `original_title` (string, nullable)
- `description` (text, nullable)
- `poster_path` (string, nullable)
- `backdrop_path` (string, nullable)
- `release_date` (date, nullable)
- `status` (string) - Backed by PHP Enum: 'ongoing', 'completed', etc.
- `duration_minutes` (integer, nullable)
- `age_rating` (string, nullable)
- `average_rating` (decimal 4,2, default 0) - Cached/Denormalized value
- `ratings_count` (unsigned bigint, default 0) - Cached/Denormalized value
- `views_count` (unsigned bigint, default 0)
- `is_featured` (boolean, default false)
- `is_published` (boolean, default false)
- `published_at` (timestamp, nullable)
- `timestamps`
- Index: (`type`, `is_published`)

### 3. `genres`
- `id` (PK, unsigned bigint)
- `name` (string)
- `slug` (string, unique)
- `timestamps`

### 4. `content_genre` (Pivot)
- `content_id` (FK to contents.id, cascade on delete)
- `genre_id` (FK to genres.id, cascade on delete)
- PK: (`content_id`, `genre_id`)

### 5. `seasons`
- `id` (PK, unsigned bigint)
- `content_id` (FK to contents.id, cascade on delete)
- `season_number` (integer)
- `title` (string, nullable)
- `description` (text, nullable)
- `poster_path` (string, nullable)
- `timestamps`
- Unique: (`content_id`, `season_number`)

### 6. `episodes`
- `id` (PK, unsigned bigint)
- `season_id` (FK to seasons.id, cascade on delete)
- `episode_number` (integer)
- `title` (string, nullable)
- `description` (text, nullable)
- `thumbnail_path` (string, nullable)
- `duration_minutes` (integer, nullable)
- `release_date` (date, nullable)
- `is_published` (boolean, default false)
- `published_at` (timestamp, nullable)
- `timestamps`
- Unique: (`season_id`, `episode_number`)
- Index: (`season_id`, `is_published`, `episode_number`)

### 7. `video_sources` (Streaming)
- `id` (PK, unsigned bigint)
- `sourceable_id` (unsigned bigint)
- `sourceable_type` (string) - Morph to Content (for Movies) or Episode
- `provider` (string) - e.g., 'direct', 'hls', 'embed', 'gdrive'
- `url` (text)
- `quality` (string, nullable) - e.g., '1080p', '720p', 'auto'
- `server_name` (string) - Display name in UI
- `language` (string, nullable) - Audio language
- `priority` (integer, default 0) - Sorting order
- `is_active` (boolean, default true)
- `timestamps`
- Index: (`sourceable_type`, `sourceable_id`)
*Note: This uses polymorphic relations purposefully to allow Movies (Content) and Episodes to both have video sources seamlessly.*

### 8. `download_sources` (Downloads)
- `id` (PK, unsigned bigint)
- `sourceable_id` (unsigned bigint)
- `sourceable_type` (string) - Morph to Content or Episode
- `provider` (string)
- `url` (text)
- `quality` (string)
- `server_name` (string)
- `file_size_bytes` (unsigned bigint, nullable)
- `priority` (integer, default 0)
- `is_active` (boolean, default true)
- `timestamps`
- Index: (`sourceable_type`, `sourceable_id`)

### 9. `subtitles`
- `id` (PK, unsigned bigint)
- `sourceable_id` (unsigned bigint)
- `sourceable_type` (string) - Morph to Content or Episode
- `language` (string)
- `label` (string) - e.g., 'English (US)'
- `file_path` (text) - URL or local path to .vtt/.srt
- `timestamps`
- Index: (`sourceable_type`, `sourceable_id`)

### 10. `favorites`
- `id` (PK, unsigned bigint)
- `user_id` (FK to users.id, cascade on delete)
- `content_id` (FK to contents.id, cascade on delete)
- `timestamps`
- Unique: (`user_id`, `content_id`)

### 11. `watchlists`
- `id` (PK, unsigned bigint)
- `user_id` (FK to users.id, cascade on delete)
- `content_id` (FK to contents.id, cascade on delete)
- `timestamps`
- Unique: (`user_id`, `content_id`)

### 12. `watch_histories`
- `id` (PK, unsigned bigint)
- `user_id` (FK to users.id, cascade on delete)
- `content_id` (FK to contents.id, cascade on delete)
- `episode_id` (FK to episodes.id, nullable, cascade on delete)
- `progress_seconds` (integer, default 0)
- `duration_seconds` (integer, nullable)
- `is_completed` (boolean, default false)
- `last_watched_at` (timestamp)
- `timestamps`
- Index: (`user_id`, `content_id`)
- Index: (`user_id`, `episode_id`)
*Note: Uniqueness (user+content for movies, user+episode for episodes) is enforced at the application level via WatchHistoryService.*

### 13. `episode_bookmarks`
- `id` (PK, unsigned bigint)
- `user_id` (FK to users.id, cascade on delete)
- `episode_id` (FK to episodes.id, cascade on delete)
- `timestamps`
- Unique: (`user_id`, `episode_id`)

### 14. `ratings` (Future Extensibility)
- `id` (PK, unsigned bigint)
- `user_id` (FK to users.id, cascade on delete)
- `content_id` (FK to contents.id, cascade on delete)
- `rating` (integer, 1-10)
- `timestamps`
- Unique: (`user_id`, `content_id`)

### 15. `comments` (Future Extensibility)
- `id` (PK, unsigned bigint)
- `user_id` (FK to users.id, cascade on delete)
- `content_id` (FK to contents.id, cascade on delete)
- `episode_id` (FK to episodes.id, nullable, cascade on delete)
- `body` (text)
- `is_approved` (boolean, default true)
- `timestamps`
- Index: (`content_id`, `is_approved`)
- Index: (`episode_id`, `is_approved`)

### 16. `reports` (Future Extensibility)
- `id` (PK, unsigned bigint)
- `user_id` (FK to users.id, nullable, nullOnDelete)
- `reportable_id` (unsigned bigint)
- `reportable_type` (string) - VideoSource, Comment, etc.
- `reason` (string)
- `details` (text, nullable)
- `status` (string, default 'pending')
- `timestamps`
- Index: (`reportable_type`, `reportable_id`)

### 17. `banners`
- `id` (PK, unsigned bigint)
- `content_id` (FK to contents.id, nullable)
- `image_path` (string)
- `title` (string, nullable)
- `link_url` (string, nullable)
- `priority` (integer, default 0)
- `is_active` (boolean, default true)
- `timestamps`

### 18. `settings`
- `key` (PK, string)
- `value` (text, nullable)
- `type` (string) - e.g., 'string', 'boolean', 'json'
- `timestamps`
