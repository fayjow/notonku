# NontonKu — Project Documentation

## Project Purpose
NontonKu is a public movie, series, anime, and donghua streaming and download platform. The project is designed to be a production-ready, maintainable, secure, responsive, SEO-friendly web application built for long-term extensibility.

## Target Users
- **Public/Guest Users:** Individuals looking to stream or download media content without the friction of mandatory account creation.
- **Registered Users:** Frequent visitors who want personalized features (watch history, favorites).
- **Administrators:** Platform managers who curate content, manage media sources, and monitor the platform.

## Supported Content Types
The platform consolidates all media into a single core entity with the following types:
- **Movie:** Standalone films with direct video/download sources.
- **Series:** Episodic live-action TV shows.
- **Anime:** Episodic Japanese animation.
- **Donghua:** Episodic Chinese animation.

## Guest Capabilities
Guest users are not forced to log in for basic browsing and consumption. They can:
- Browse content catalogs and genres.
- Search for specific titles.
- View detailed content pages.
- Watch streaming media.
- Download media files.

## Authenticated User Capabilities
Authentication unlocks personalized features:
- Manage Favorites.
- Add to Watchlist.
- Track Watch History.
- Continue Watching (resume from last timestamp).
- Bookmark specific episodes.
*(Note: Some features like ratings, comments, and reports are planned for future extensibility but require authentication when implemented).*

## Admin Capabilities
Administrators have access to a secure, server-side authenticated backend to:
- Manage content (CRUD for movies, series, anime, donghua).
- Manage seasons and episodes.
- Manage media sources (streaming URLs, download links).
- Manage genres and taxonomy.
- Monitor basic platform metrics.
- *(Future roles like Super Admin, Editor, and Moderator are architecturally anticipated but not exposed in the initial version).*

## Current Scope
The immediate focus is building a robust foundation for a streaming/download platform:
- Laravel and Blade foundation.
- Provider-agnostic media source architecture.
- Core content management (movies, series, episodes).
- Light/Dark mode responsive UI using Tailwind CSS.
- SEO-first implementation (Meta, Open Graph, JSON-LD).
- Admin CMS for content and source management.

## Future Scope
The architecture is designed to accommodate the following without requiring a core rewrite:
- Multiple admin roles (Super Admin, Editor, Moderator).
- User interaction features (Comments, Ratings, Reports).
- Subtitle management.
- User notifications.
- Advertisement integration.
- Multiple/external storage providers (S3, Cloudflare R2).
- Native API for potential mobile applications.
- CDN integration.
- Recommendation systems.

## Non-Goals
- **Single Page Application (SPA):** We will not use React, Vue, or Inertia for the core frontend. It is a traditional server-rendered application using Blade and Alpine.js.
- **Walled Garden:** We will not force users to create accounts just to watch or browse.
- **Hardcoded Providers:** We will not build the app tightly coupled to a single specific streaming API or download host.
