# NontonKu — Architecture Documentation

## Core Architecture

NontonKu follows a traditional MVC (Model-View-Controller) architecture utilizing the Laravel framework, enhanced by a Service Layer to encapsulate business logic. The frontend is server-rendered using Blade, styled with Tailwind CSS, and sprinkled with Alpine.js for lightweight interactivity.

### ASCII Architecture Diagram

```text
       [Client / Browser]
              │ (HTTP/HTTPS)
              ▼
        [Route Layer] ─────────┐
              │                │ (Validation)
              ▼                ▼
       [Controller] ◄───── [Form Requests]
              │                ▲
              │ (AuthZ)        │
              ├──► [Policies / Gates]
              │
              ▼
       [Service Layer] ◄────── (Business Logic & External APIs)
              │
              ▼
        [Model (ORM)] ◄─────── [Events / Jobs / Queues]
              │
              ▼
         [Database]
```

## Layer Responsibilities

### Controllers
- **Responsibilities:** Receive HTTP requests, delegate validation to Form Requests, check authorization via Policies, call the appropriate Service layer methods, and return the HTTP response (Blade view or JSON).
- **Rule:** Controllers must remain thin. Large blocks of business logic are strictly forbidden here.

### Service Layer
- **Responsibilities:** Encapsulate complex business logic, handle provider integrations (e.g., `StreamingService`, `DownloadService`), and orchestrate multiple model interactions.
- **Rule:** Keeps controllers clean and allows business logic to be reused (e.g., from an artisan command or a queued job).

### Models
- **Responsibilities:** Data representation, Eloquent relationships, attribute casting, and data access encapsulation.
- **Rule:** Define clear relationships (`hasMany`, `belongsTo`, etc.). Use `$fillable` or `$guarded` carefully to prevent mass-assignment vulnerabilities. Avoid unnecessary polymorphic relationships.

### Form Requests
- **Responsibilities:** Extract validation rules and authorization logic from the controller.
- **Rule:** All external input (user, admin, URLs, files) must be validated using Form Requests for non-trivial endpoints.

### Policies & Gates
- **Responsibilities:** Centralized authorization logic.
- **Rule:** Admin routes and personal user actions (e.g., deleting a favorite) must be protected by Policies. Hidden UI elements are not a security mechanism.

## Frontend Architecture

### Blade Architecture
- **Responsibilities:** Presentation and server-side rendering of HTML.
- **Rule:** Avoid complex PHP logic or direct database queries within Blade templates. Use View Composers or pass pre-processed data from the controller.

### Blade Components
- **Responsibilities:** Encapsulate reusable UI elements (e.g., `<x-content-card>`, `<x-button>`, `<x-modal>`).
- **Rule:** Components must support both Light and Dark modes. Markup for common elements like movie cards must not be duplicated across pages.

### Tailwind CSS & Alpine.js
- **Tailwind:** Utility-first styling. Reusable components and shared design tokens should be abstracted appropriately. Avoid random one-off styles for existing components.
- **Alpine.js:** Used for localized interactivity (modals, dropdowns, tabs, theme toggling) without the overhead of a heavy JavaScript framework.

## Admin Architecture
- **Structure:** The Admin CMS is a protected area of the application governed by standard Laravel authentication and authorization (Gates/Policies).
- **Design:** Follows a clean dashboard layout, distinct from the public UI but utilizing the same core component library where applicable.
- **Future-proofing:** The authentication system uses a role-based architecture. The current system will start with `Admin`, while structurally supporting future granular roles such as `Editor` and `Moderator`.

## Extensibility Strategy
- **Media Providers:** Abstracted behind `StreamingService` and `DownloadService`. Adding a new provider involves creating a new provider class/driver that implements a common interface, requiring zero changes to the core `Content` or `Episode` models.
- **Feature Flags / Modules:** Infrastructure for features like ratings, comments, and reports will be designed into the database, but the UI will remain hidden until fully implemented.
