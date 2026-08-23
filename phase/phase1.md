# NontonKu — Phase 1: Laravel Foundation

Phase 0 is complete and the architecture/database documentation is finalized.

Before writing code, read and follow:

- docs/PROJECT.md
- docs/ARCHITECTURE.md
- docs/DATABASE.md
- docs/UI.md
- docs/STREAMING.md
- docs/SECURITY.md
- docs/SEO.md
- docs/ROADMAP.md
- AGENTS.md
- phase0.md

Do not modify the finalized database architecture unless a critical technical issue is discovered.

## Objective

Set up the Laravel application foundation for NontonKu.

The goal of this phase is ONLY to establish a clean, maintainable Laravel foundation.

Do NOT implement the complete NontonKu application yet.

## Technology

Use:

- Laravel
- PHP
- Blade
- Tailwind CSS
- Alpine.js
- Vite
- MySQL
- Laravel Eloquent ORM

Use the latest stable Laravel version compatible with the environment.

Before installation, inspect the existing project/environment and determine the installed PHP, Composer, Node.js and npm versions.

Do not unnecessarily upgrade system-wide dependencies.

## Frontend

The frontend must use:

- Blade templates
- Tailwind CSS
- Alpine.js
- Vite

Do NOT introduce:

- React
- Vue
- Inertia
- Livewire

unless explicitly requested later.

The application should remain primarily server-rendered.

## Authentication

Prepare the authentication foundation.

Authentication requirements:

- Public users can browse the website without logging in.
- Login is required only for user features such as:
    - Favorite
    - Watchlist
    - Continue Watching
    - Watch History
    - Episode Bookmark
    - Future Rating
    - Future Comment
    - Future Report

Admin authentication must be separated logically from public user functionality.

Use Laravel's standard authentication mechanisms.

Do not build the full admin CMS yet.

## Authorization

For v1, use the `users.role` field defined in DATABASE.md.

Supported roles:

- user
- admin
- editor
- moderator

Only `admin` should have administrative access initially.

However, structure authorization so it can later migrate to a granular permission system without rewriting controllers.

Use:

- Middleware
- Policies
- Gates where appropriate

Do not rely on hiding UI elements for authorization.

## Application Structure

Prepare a clean structure for:

app/
├── Enums/
├── Http/
│ ├── Controllers/
│ ├── Requests/
│ └── Middleware/
├── Models/
├── Policies/
├── Services/
│ ├── Streaming/
│ ├── Download/
│ └── Content/
├── Providers/
└── Support/

Do not create unnecessary classes just for the sake of abstraction.

Create directories/classes only when they are needed by the current phase or clearly required by the architecture.

## Blade Structure

Prepare:

resources/views/

with a maintainable structure such as:

layouts/
components/
pages/
auth/
admin/

Do not create every future page yet.

Create only the base layout required for this phase.

## Base Layout

Create a basic application layout containing:

- HTML5 structure
- responsive viewport
- Tailwind integration
- Vite integration
- Alpine.js integration
- CSRF meta tag where appropriate
- basic SEO placeholder structure
- dark mode support
- accessible navigation foundation
- flash message area
- validation error area

Do not build the final homepage yet.

## Theme

NontonKu uses:

Light mode:

- predominantly white
- clean
- modern
- minimal
- subtle borders
- subtle shadows

Dark mode:

- dark neutral background
- readable contrast
- not pure black everywhere

Use Tailwind's dark mode system.

Theme preference should persist across page navigation.

Avoid excessive animations.

## Design Tokens

Establish reusable Tailwind conventions for:

- background
- surface
- text
- muted text
- border
- primary
- danger
- success
- warning

Do not scatter arbitrary color values throughout templates.

The final visual design will be refined in the UI phase.

## Components

Create only foundational reusable Blade components, for example:

- button
- input
- alert
- badge
- container
- card

Do not create content cards yet unless necessary.

Components must support light/dark mode.

## Environment

Prepare:

- .env.example
- application configuration
- database configuration
- cache/session configuration
- filesystem configuration

Do not commit secrets.

Verify `.gitignore`.

## Code Quality

Follow:

- PSR-12
- Laravel conventions
- strict typing where appropriate
- meaningful class and method names
- small focused methods
- thin controllers
- no database queries inside Blade
- no business logic inside Blade
- no business logic inside routes

## Testing

Prepare the testing infrastructure.

At minimum create tests verifying:

1. Application loads successfully.
2. Public route returns HTTP 200.
3. Authentication pages work.
4. Unauthenticated users cannot access admin routes.
5. Authenticated normal users cannot access admin routes.
6. Admin users can access the admin area placeholder.
7. Dark mode foundation does not break rendering.

Use Laravel's testing conventions.

## Git

Do not commit automatically.

Do not push anything to a remote repository.

Show the files changed after implementation.

## Important Restrictions

Do NOT implement:

- Content CRUD
- Movie CRUD
- Anime CRUD
- Donghua CRUD
- Series CRUD
- Episode CRUD
- Streaming server management
- Download server management
- Watchlist functionality
- Favorite functionality
- Watch history functionality
- Rating
- Comment
- Report
- Sitemap generation
- PWA
- SEO implementation
- Admin content management

Those belong to later phases.

## Completion Requirements

When finished, provide:

1. Laravel version
2. PHP version detected
3. Node/npm version detected
4. Packages installed
5. Files created/modified
6. Authentication approach
7. Authorization approach
8. Blade structure
9. Tailwind configuration
10. Alpine configuration
11. Dark mode implementation
12. Tests created and their results
13. Any warnings or unresolved issues
14. Recommended next phase

Do not proceed to Phase 2 automatically.

Stop after Phase 1 is complete and wait for further instructions.
