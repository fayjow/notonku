# NontonKu — AI Agent Instructions

## 1. Project Identity

NontonKu is a public movie, series, anime, and donghua streaming and download platform.

The project must be production-ready, maintainable, secure, responsive, SEO-friendly, and designed for long-term extensibility.

---

## 2. Technology Stack

Use:

* Laravel
* Blade Templates
* Tailwind CSS
* Alpine.js
* Vite
* MySQL
* Eloquent ORM

Do not introduce React, Vue, Bootstrap, jQuery, Inertia, or Livewire unless explicitly approved by the project owner.

Use native Laravel features whenever possible.

Avoid unnecessary dependencies.

---

## 3. Core Architecture

Follow this general architecture:

Request
→ Controller
→ Service
→ Model
→ Database

Use:

* Form Requests for validation
* Policies/Gates for authorization
* Services for business logic
* Blade Components for reusable UI
* Eloquent relationships for database relationships
* Events/Listeners when appropriate
* Jobs/Queues for long-running operations

Controllers must remain thin.

Do not place large business logic blocks inside Controllers.

Do not place business logic inside Blade templates.

---

## 4. Content Architecture

The primary content model is `Content`.

Supported content types:

* movie
* series
* anime
* donghua

Do not create separate primary tables for movies, anime, donghua, and series unless explicitly approved.

Use:

Content
→ Seasons
→ Episodes

Movies may use content-level sources directly.

Series, anime, and donghua may use season and episode sources.

---

## 5. Media Source Architecture

Streaming and download systems must be provider-agnostic.

Do not hardcode the application around one streaming provider.

Use a source abstraction.

Streaming:

StreamingService
→ VideoSource
→ Provider

Download:

DownloadService
→ DownloadSource
→ Provider

The architecture should be extensible to:

* Direct video
* HLS
* Embed
* External provider
* Cloud storage
* CDN
* Future providers

Adding a new provider should not require rewriting the core content system.

---

## 6. Database Rules

Every database change must use Laravel migrations.

Before creating or modifying a migration:

1. Inspect existing migrations.
2. Inspect related models.
3. Inspect existing relationships.
4. Check for duplicate functionality.
5. Consider backward compatibility.
6. Add appropriate indexes.
7. Add foreign keys where appropriate.
8. Consider nullable/cascade behavior carefully.

Do not create duplicate migrations for the same schema change.

Do not modify production database assumptions without documenting the change.

---

## 7. Model Rules

Models must define clear relationships.

Use:

* belongsTo
* hasMany
* hasOne
* belongsToMany
* morph relationships only when genuinely useful

Avoid unnecessary polymorphic relationships.

Use casts when appropriate.

Use guarded/fillable carefully.

Avoid mass-assignment vulnerabilities.

---

## 8. Validation

All external input must be validated.

Use Form Request classes for non-trivial validation.

Never trust:

* user input
* admin input
* uploaded files
* URLs
* query parameters
* route parameters

Validate source URLs before storing them.

Validate uploaded media according to allowed MIME types, extensions, size, and storage rules.

---

## 9. Authorization

Admin functionality must be protected by authentication and authorization.

Never use:

* hardcoded email addresses
* hardcoded usernames
* hidden UI elements as the only security mechanism

Authorization must happen server-side.

Prepare the architecture for future roles such as:

* Super Admin
* Admin
* Editor
* Moderator

The initial version may only expose Admin.

---

## 10. Authentication

Guest users can:

* browse
* search
* view content
* watch
* download

Authentication is required for personal features such as:

* favorites
* watchlist
* history
* continue watching
* episode bookmarks

Do not force login for ordinary browsing.

---

## 11. User Features

Prepare infrastructure for:

* favorites
* watchlists
* watch history
* continue watching
* episode bookmarks
* ratings
* comments
* reports
* subtitles
* notifications

Features that are not currently enabled should not create unnecessary visible UI.

Infrastructure may exist without exposing unfinished functionality.

---

## 12. UI Principles

NontonKu uses a clean, minimalist interface.

Primary visual direction:

* White dominant light mode
* Dark mode
* Mobile-first
* Responsive
* Clean spacing
* Consistent typography
* Minimal unnecessary decoration
* Limited animations
* Accessible interactions

Avoid excessive:

* gradients
* shadows
* rounded containers
* animations
* decorative elements

The UI should feel like a modern media platform, not an admin dashboard.

---

## 13. Tailwind Rules

Use Tailwind CSS consistently.

Use reusable components and shared design tokens where appropriate.

Do not create random one-off styles for components that already have reusable equivalents.

Before creating a new component, check whether an existing component can be reused.

Do not duplicate movie/content card markup across multiple pages.

---

## 14. Blade Rules

Use reusable Blade components.

Examples:

* ContentCard
* MovieCard
* EpisodeCard
* Button
* Badge
* Modal
* SectionHeader
* Rating
* Poster

Blade should primarily handle presentation.

Avoid complex PHP logic inside Blade.

Do not perform database queries directly inside Blade templates.

---

## 15. Dark Mode

Dark mode is a first-class feature.

Do not implement dark mode as an afterthought.

Every reusable UI component must be reviewed for both:

* Light mode
* Dark mode

Theme preference should persist across page navigation.

Avoid colors that become unreadable in either theme.

---

## 16. Responsive Design

Design mobile-first.

Verify:

* mobile
* tablet
* desktop

Important pages:

* homepage
* search
* content detail
* watch page
* admin dashboard
* forms

Do not assume desktop-only layouts.

---

## 17. SEO

SEO is a core feature.

Public content pages should support:

* title
* meta description
* canonical URL
* Open Graph
* social sharing metadata
* JSON-LD
* breadcrumbs
* sitemap

Use appropriate structured data such as:

* Movie
* TVSeries
* VideoObject
* WebSite
* Organization
* BreadcrumbList

Avoid duplicate metadata.

---

## 18. Performance

Use:

* eager loading
* pagination
* database indexes
* caching where appropriate
* optimized queries
* image optimization
* lazy loading
* efficient Blade rendering

Avoid N+1 queries.

Do not load unnecessary relationships.

Do not fetch all records when pagination is appropriate.

---

## 19. Security

Always consider:

* CSRF
* XSS
* SQL injection
* mass assignment
* authentication
* authorization
* rate limiting
* session security
* file upload security
* URL validation
* admin protection
* security headers

Never expose secrets in source code.

Never commit `.env`.

Never hardcode API keys, passwords, tokens, or credentials.

---

## 20. Testing

After implementing meaningful functionality:

Run:

`php artisan test`

For frontend/build changes:

`npm run build`

Fix errors before considering the task complete.

Create tests for important business behavior.

Do not remove existing tests merely to make the suite pass.

---

## 21. Browser Verification

For UI changes, verify the result in a real browser when browser tooling is available.

Check:

* layout
* responsiveness
* dark mode
* navigation
* forms
* console errors
* broken links
* visual consistency

Do not assume successful compilation means successful UI implementation.

---

## 22. Agent Workflow

Before implementing a major feature:

1. Understand the requirement.
2. Inspect the existing project.
3. Inspect related files.
4. Identify dependencies.
5. Produce a concise implementation plan.
6. Implement incrementally.
7. Run tests.
8. Run build when relevant.
9. Perform browser verification when relevant.
10. Fix discovered issues.
11. Summarize changes.

---

## 23. Ambiguity Rule

Do not silently make major architectural decisions when requirements are ambiguous.

For minor implementation details:

* choose the simplest production-ready solution
* document the decision

For major decisions affecting:

* database architecture
* authentication
* storage
* streaming architecture
* external providers
* deployment
* security

ask the project owner before proceeding if the decision cannot be safely inferred.

---

## 24. Change Safety

Before modifying an existing feature:

1. Inspect current implementation.
2. Understand dependencies.
3. Avoid unnecessary rewrites.
4. Preserve working functionality.
5. Make the smallest clean change that satisfies the requirement.
6. Run relevant tests.

Do not rewrite large portions of the application merely to implement a small feature.

---

## 25. Dependency Policy

Do not install packages without a clear reason.

Before adding a package:

1. Check whether Laravel already provides the functionality.
2. Check whether an existing project dependency can solve it.
3. Evaluate maintenance and security implications.
4. Explain why the package is needed.

Prefer fewer dependencies.

---

## 26. Documentation

Important architectural decisions must be documented.

Maintain:

* `docs/PROJECT.md`
* `docs/ARCHITECTURE.md`
* `docs/DATABASE.md`
* `docs/UI.md`
* `docs/STREAMING.md`
* `docs/SECURITY.md`
* `docs/SEO.md`
* `docs/ROADMAP.md`

Update relevant documentation when architecture changes.

---

## 27. Future Extensibility

The application should be designed so future functionality can be added without rewriting the core system.

Potential future functionality:

* multiple admin roles
* comments
* ratings
* subtitles
* notifications
* advertisements
* multiple storage providers
* multiple streaming providers
* multiple download providers
* API
* mobile application
* CDN
* recommendation system

Do not implement speculative complexity unless it provides clear architectural value.

---

## 28. Definition of Done

A feature is not complete merely because its code exists.

A feature is considered complete when:

* implementation works
* validation exists
* authorization exists where required
* database relationships work
* UI is responsive
* light mode works
* dark mode works
* tests pass
* build passes when relevant
* no obvious console errors exist
* no obvious N+1 query exists
* documentation is updated when architecture changes

---

## 29. Important Rule

Do not optimize for the number of files or amount of generated code.

Optimize for:

* correctness
* maintainability
* security
* simplicity
* consistency
* extensibility

When two solutions are valid, prefer the simpler solution that keeps future extension possible.
