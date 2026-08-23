# NontonKu — Security Requirements

## Authentication & Authorization
- **Authentication:** Standard Laravel Session-based authentication for both regular users and admins. No JWT or stateless API auth is required for the web frontend.
- **Authorization:** All admin routes must be protected by a `CheckAdmin` middleware and Laravel Policies/Gates.
- **Personal Data:** User-specific endpoints (Favorites, Watchlist) must verify that the authenticated user owns the resource being accessed or modified.

## Input Validation
- **Rule:** Never trust user or admin input.
- **Implementation:** All `POST`, `PUT`, `PATCH` requests must route through Laravel Form Requests.
- **Source URLs:** Media URLs provided by admins must be strictly validated. Validate URL format and prevent insertion of malicious JavaScript (`javascript:` URIs).
- **File Uploads:** Uploads (like Posters or Subtitles) must be validated against strict MIME types, file extensions, and maximum file size limits. Ensure files are stored in a non-executable directory (e.g., public storage with proper symlinks).

## Web Vulnerabilities Prevention
- **CSRF:** All state-changing operations must include a valid CSRF token (`@csrf` in Blade).
- **XSS (Cross-Site Scripting):** All user-generated content must be escaped upon output. Blade's `{{ }}` handles this automatically. For admin-provided content that requires HTML (e.g., descriptions), use a secure HTML purifier before rendering with `{!! !!}`.
- **SQL Injection:** Strict adherence to Eloquent ORM. Never use raw queries (`DB::raw()`) with unescaped user input.
- **Mass Assignment:** Models must define `$fillable` (or `$guarded`) accurately. Never pass `$request->all()` directly to a model creation or update method.

## Infrastructure Security
- **Rate Limiting:** Protect authentication routes (login, register, password reset) and potentially heavy API endpoints (search) using Laravel's Rate Limiter.
- **Session Security:** Ensure sessions expire after a reasonable time. Use secure, HttpOnly cookies.
- **Secrets:** Never hardcode API keys, database credentials, or application keys in the source code. All secrets must reside in `.env` and be accessed via `config()`. `.env` must be in `.gitignore`.
- **Security Headers:** Prepare middleware to append security headers (X-Frame-Options, X-XSS-Protection, X-Content-Type-Options, Strict-Transport-Security) where appropriate.

## Logging
- Log important security events (failed logins, admin role assignments, destructive actions in the CMS).
