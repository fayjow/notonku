# NontonKu — Streaming & Media Architecture

## Overview
The media delivery system in NontonKu is strictly **provider-agnostic**. The core application does not know or care how a video is delivered, only that a media source exists and conforms to an interface. This ensures the platform can easily pivot to new storage providers or third-party streaming services in the future.

## Abstraction Layers

### 1. Database Layer (`video_sources` & `download_sources`)
As defined in the Database architecture, media sources are polymorphic. A source belongs to either a `Content` (Movie) or an `Episode` (Series/Anime).
- `provider`: A string identifier (e.g., `direct`, `embed`, `hls`, `youtube`, `s3`).
- `url`: The raw URL or identifier required by the provider.
- `server_name`: User-facing name (e.g., "Server 1 - VIP", "Fast Stream").

### 2. Service Layer (`StreamingService` & `DownloadService`)
These services act as factories/managers. When the controller requests video sources for a specific movie, the `StreamingService`:
1. Fetches the `video_sources` records from the database.
2. Checks the `provider` string.
3. Instantiates the appropriate Provider Adapter.
4. Returns standardized data to the frontend.

### 3. Provider Adapters (The Abstraction)
We define a `VideoProviderInterface`:
```php
interface VideoProviderInterface {
    public function render(VideoSource $source): string; // Returns HTML (e.g., iframe, video tag)
    public function getStreamUrl(VideoSource $source): string; // Returns raw URL if applicable
    public function supportsSubtitles(): bool;
}
```

## Potential Future Source Types

By implementing `VideoProviderInterface`, we can support:

- **Direct Video (MP4):** Renders an HTML5 `<video>` player pointing directly to the `url`.
- **HLS (.m3u8):** Renders a player (like Video.js or Plyr) configured for HLS streaming.
- **Embed:** Renders a standard `<iframe>` with the `url` as the `src`.
- **Cloud Storage (S3/R2):** The adapter generates a pre-signed, temporary URL before rendering the HTML5 player, protecting the origin bucket.
- **External Provider (API):** The adapter uses the `url` (acting as an ID) to query a 3rd party API, retrieve the actual stream URL, and render the player.

## Source Selection and Priority
- Multiple sources can exist for a single movie/episode.
- Sources have a `priority` integer field.
- The UI will present sources as selectable "Servers" or "Qualities", ordered by `priority` (highest first).
- If one provider goes down, the user can simply select the next server in the UI.

## Download Architecture
Similar to streaming, the `DownloadService` manages `DownloadSource` records.
- Providers might include `direct`, `gdrive`, `mega`, etc.
- The controller will pass the request to the `DownloadService`, which handles any necessary link generation or redirection based on the specific provider adapter.
