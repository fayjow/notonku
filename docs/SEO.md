# NontonKu — SEO Strategy

## Overview
SEO is a core feature for NontonKu to ensure high visibility on search engines for movies, series, and anime titles.

## Meta Tags
- **Page Titles:** Dynamic and descriptive. 
  - Format: `[Content Title] ([Year]) - Watch Online | NontonKu`
  - Episode Format: `[Series Title] Season [X] Episode [Y] | NontonKu`
- **Meta Descriptions:** Auto-generated from the content description, truncated to ~155 characters. Includes keywords like "Watch", "Stream", "Download".
- **Canonical URLs:** Every page must have a `<link rel="canonical" href="...">` tag pointing to the absolute, primary URL of the content to prevent duplicate content penalties (e.g., if accessed via multiple category paths).

## Social Sharing (Open Graph & Twitter Cards)
Every content and episode page will include:
- `og:title` / `twitter:title`
- `og:description` / `twitter:description`
- `og:image` / `twitter:image` (Using the `backdrop_path` or `poster_path`)
- `og:url`
- `og:type` (set to `video.movie` or `video.tv_show`)

## Structured Data (JSON-LD)
We will embed Schema.org JSON-LD in the `<head>` of relevant pages to enable rich snippets in Google Search results.
- **Homepage:** `WebSite`, `Organization`
- **Movie Page:** `Movie` (Includes `name`, `image`, `description`, `dateCreated`, `aggregateRating`)
- **Series Page:** `TVSeries`
- **Episode/Watch Page:** `VideoObject`, `TVEpisode`
- **All Pages:** `BreadcrumbList` for clear site navigation structure.

## Architecture & Crawlability
- **Sitemap:** Dynamic XML sitemap generation (`/sitemap.xml`) listing all public contents, seasons, and episodes.
- **Robots.txt:** Clear directives, allowing crawling of public pages while disallowing indexing of admin routes (`/admin/*`) and user account pages (`/profile/*`).
- **Pagination SEO:** Use `<link rel="next">` and `<link rel="prev">` on browse and category pages. Ensure URL parameters for pagination (e.g., `?page=2`) are handled correctly to avoid infinite crawl spaces.
- **Breadcrumbs:** Visual and structural breadcrumbs (Home > Anime > Title > Episode 1) linked properly.
