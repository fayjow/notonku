# NontonKu — Phase 3: Design System & Public Application Shell

Phase 0, Phase 1, and Phase 2 are complete.

Current verification:

- 41 tests passed
- 96 assertions passed
- migrations successful
- seed successful
- authentication working
- admin authorization working
- database relationships working
- polymorphic relationships working
- WatchHistoryService working

Do NOT modify the finalized database architecture.

Before implementation, read:

- docs/PROJECT.md
- docs/ARCHITECTURE.md
- docs/DATABASE.md
- docs/UI.md
- docs/STREAMING.md
- docs/SECURITY.md
- docs/SEO.md
- docs/ROADMAP.md
- AGENTS.md

# Objective

Create the visual foundation of NontonKu.

This phase focuses ONLY on:

- Design system
- Public application layout
- Responsive navigation
- Footer
- Theme system
- Reusable Blade UI components
- Accessibility foundation

Do NOT implement the actual catalog, streaming, downloading, or CMS.

# Technology

Use only:

- Laravel
- Blade
- Tailwind CSS
- Alpine.js
- Vite

Do NOT introduce:

- React
- Vue
- Inertia
- Livewire

Keep the application server-rendered.

# Design Direction

NontonKu must have a clean modern streaming-platform aesthetic.

Primary visual direction:

- clean
- minimal
- modern
- content-focused
- predominantly white in light mode
- dark neutral surfaces in dark mode
- subtle borders
- subtle shadows
- rounded corners
- restrained animations

Avoid:

- excessive gradients
- excessive glassmorphism
- excessive animations
- overly colorful UI
- huge decorative elements
- visually noisy layouts

The content posters should remain the visual focus.

# Color System

Create reusable design tokens for:

- background
- surface
- elevated surface
- text
- muted text
- border
- primary
- primary hover
- success
- warning
- danger

Light mode:

background should be predominantly white/light neutral.

Dark mode:

background should be dark neutral rather than absolute black.

Do not scatter arbitrary colors throughout Blade files.

# Typography

Use a clean modern sans-serif typography system.

Establish:

- display heading
- heading
- body
- small
- muted

Ensure readable contrast in both themes.

# Dark Mode

Implement a proper theme system.

Requirements:

- light mode
- dark mode
- system/default preference
- manual toggle
- persistence across refresh
- no visible flash where reasonably avoidable

Use Tailwind dark mode.

Use Alpine.js only where useful.

Do not introduce a third-party theme package.

# Public Layout

Create:

resources/views/layouts/

with a reusable public application layout.

The layout should contain:

Header
Main
Footer

The public layout should be usable by:

- homepage
- catalog
- content detail
- search
- future watch page

# Header

Create a responsive header.

Desktop:

Logo
Home
Movies
Series
Anime
Donghua
Search
Theme toggle
Login / user menu

Mobile:

Logo
Search
Theme toggle
Menu button

Use Alpine.js for the mobile menu.

The navigation must be keyboard accessible.

Do not use JavaScript for navigation that can be handled by normal links.

# Logo

Create a simple text-based NontonKu brand foundation.

Do not create an image logo yet.

Use:

NontonKu

The logo component should be reusable.

# Search

Create a reusable search input component.

At this stage:

- UI only
- form can point to a placeholder search route
- no actual search implementation

Do not implement search backend.

# Footer

Create a clean responsive footer.

Include:

NontonKu
Short description
Navigation links
Copyright

Do not add fake social media links.

# Blade Components

Create reusable components such as:

<x-container>
<x-button>
<x-input>
<x-card>
<x-badge>
<x-alert>
<x-section-heading>
<x-empty-state>
<x-modal>
<x-dropdown>

Only create components that are genuinely reusable.

Do not create unnecessary abstractions.

# Content Card Foundation

Create a reusable content-card component suitable for:

- movie
- series
- anime
- donghua

The component should support:

- poster
- title
- type
- year
- rating
- badge
- optional favorite button

However:

DO NOT implement actual favorite functionality yet.

The favorite button may be a visual placeholder.

The component must be responsive.

Example conceptual structure:

Poster
Title
Metadata
Rating

Do not connect it to catalog queries yet.

# Responsive Design

Use mobile-first Tailwind design.

Target:

- mobile
- tablet
- desktop
- large desktop

Avoid fixed-width layouts that break on mobile.

Content grids should adapt naturally.

# Accessibility

Implement:

- semantic HTML
- labels for form inputs
- keyboard navigation
- visible focus states
- aria-label where needed
- buttons for actions
- links for navigation
- sufficient color contrast

Do not rely on color alone to communicate state.

# Motion

Animations should be subtle.

Allowed:

- hover transition
- dropdown transition
- mobile menu transition
- modal transition

Avoid:

- excessive page animations
- autoplay animation
- distracting effects

Respect:

prefers-reduced-motion

# Pages

Create only placeholder public pages:

/ Home placeholder
/movies Movies placeholder
/series Series placeholder
/anime Anime placeholder
/donghua Donghua placeholder

These pages should use the public layout.

Do not implement actual content querying yet.

Use small static placeholder data if needed.

# Route Naming

Use named routes.

Examples:

home
movies
series
anime
donghua

Do not hardcode internal URLs throughout Blade.

# Authentication UI

Integrate existing Breeze authentication into the new design system.

Logged-out header:

Login
Register

Logged-in header:

Profile
Logout

Admin link should only appear for admin users.

Do not expose admin functionality to normal users.

# Admin

Do not redesign the admin CMS yet.

Only preserve the existing Phase 1 admin authorization.

# SEO Foundation

Prepare the public layout for future SEO.

Include placeholders for:

- title
- meta description
- canonical
- Open Graph
- Twitter/social metadata

Do not implement dynamic SEO yet.

Use Blade sections/stacks appropriately.

# Components

Components must support both light and dark modes.

Do not duplicate the same markup for light and dark themes.

Use Tailwind dark: variants.

# JavaScript

Use Alpine.js only for:

- mobile navigation
- theme selector
- dropdown
- modal

Avoid large custom JavaScript files.

# Testing

Create tests for:

1. Home page returns 200.
2. Movies page returns 200.
3. Series page returns 200.
4. Anime page returns 200.
5. Donghua page returns 200.
6. Navigation links use named routes.
7. Admin link is only visible to admins.
8. Guest users see Login/Register.
9. Authenticated users see user menu.
10. Theme toggle markup exists.
11. Public pages render successfully in both authenticated and guest contexts.

Do not write brittle tests that depend on exact Tailwind generated CSS.

# Visual Verification

If the development environment allows browser inspection, inspect the pages manually.

Verify:

- mobile layout
- desktop layout
- navigation
- dark mode
- cards
- buttons
- forms
- footer
- accessibility focus states

Fix obvious visual problems before completing the phase.

# Restrictions

Do NOT implement:

- database queries for catalog
- search backend
- filtering
- sorting
- streaming player
- download system
- favorite functionality
- watchlist functionality
- watch history functionality
- rating
- comments
- reports
- admin CMS
- real content management
- external streaming providers

Those belong to future phases.

# Verification

Run:

php artisan test

Ensure all existing Phase 1 and Phase 2 tests continue passing.

Do not remove or weaken existing tests.

# Completion Report

Provide:

1. Components created
2. Layouts created
3. Pages created
4. Routes created
5. Theme implementation
6. Responsive behavior
7. Accessibility implementation
8. Authentication UI integration
9. Tests created
10. Full test result
11. Any warnings/errors
12. Files changed

Do NOT automatically start Phase 4.

Stop after Phase 3.
