# NontonKu — Phase 0: Project Planning

You are the lead software architect for the NontonKu project.

Before writing application code, analyze the project requirements and create the technical foundation.

Read `AGENTS.md` first and follow it strictly.

## Objective

Create the complete technical planning documentation for NontonKu.

Do NOT implement the Laravel application yet.

Do NOT create migrations yet.

Do NOT create controllers yet.

Do NOT create Blade pages yet.

This phase is planning only.

## Required Documents

Create:

`docs/PROJECT.md`

`docs/ARCHITECTURE.md`

`docs/DATABASE.md`

`docs/UI.md`

`docs/STREAMING.md`

`docs/SECURITY.md`

`docs/SEO.md`

`docs/ROADMAP.md`

## PROJECT.md

Document:

- project purpose
- target users
- supported content types
- guest capabilities
- authenticated user capabilities
- admin capabilities
- current scope
- future scope
- non-goals

## ARCHITECTURE.md

Document:

- Laravel architecture
- Blade architecture
- service layer
- controller responsibilities
- model responsibilities
- Form Requests
- Policies
- Blade components
- frontend architecture
- admin architecture
- extensibility strategy

Include an ASCII architecture diagram.

## DATABASE.md

Design the database schema.

Include:

- tables
- columns
- primary keys
- foreign keys
- indexes
- unique constraints
- relationships
- cascade behavior
- nullable fields
- future extensibility

Cover:

- users
- contents
- genres
- content_genre
- seasons
- episodes
- video_sources
- download_sources
- subtitles
- favorites
- watchlists
- watch_histories
- episode_bookmarks
- ratings
- comments
- reports
- banners
- settings

Do not create migrations yet.

## UI.md

Define the NontonKu design system.

Include:

- color tokens
- typography
- spacing
- responsive breakpoints
- light mode
- dark mode
- buttons
- cards
- forms
- navigation
- footer
- content cards
- episode cards
- player UI
- admin UI

Document the major public pages.

## STREAMING.md

Design a provider-agnostic media architecture.

Support future:

- direct video
- HLS
- embed
- external provider
- cloud storage
- CDN

Explain:

- VideoSource
- StreamingService
- provider abstraction
- download abstraction
- source priority
- quality handling
- future provider extension

Do not implement it yet.

## SECURITY.md

Define security requirements for:

- authentication
- authorization
- admin
- validation
- source URLs
- file uploads
- rate limiting
- sessions
- CSRF
- XSS
- SQL injection
- mass assignment
- secrets
- logging
- security headers

## SEO.md

Define:

- page titles
- meta descriptions
- canonical URLs
- Open Graph
- social sharing
- JSON-LD
- breadcrumbs
- sitemap
- content schema
- pagination SEO
- duplicate content prevention

## ROADMAP.md

Create implementation phases:

0. Planning
1. Laravel foundation
2. Database
3. Authentication
4. Design system
5. Public frontend
6. Content detail
7. Streaming
8. Download
9. User features
10. Admin CMS
11. SEO
12. PWA
13. Security
14. Performance
15. Testing
16. Production

For each phase define:

- objectives
- tasks
- dependencies
- acceptance criteria

## Important

Do not code the application during this phase.

Do not install unnecessary packages.

Do not make assumptions about external streaming providers.

If a major architectural decision is ambiguous, document the options and recommendation instead of implementing one.

At the end, provide a concise summary of:

- files created
- architectural decisions
- unresolved questions
- recommended next phase
