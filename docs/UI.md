# NontonKu — UI & Design System

## Design Philosophy
NontonKu utilizes a clean, minimalist, and modern interface. It is designed mobile-first, highly responsive, and treats Dark Mode as a first-class citizen. 

## Tokens & Variables (Tailwind)

### Typography
- **Primary Font:** Inter (or similar modern sans-serif like Roboto/Outfit).
- **Headings:** Bold, clean tracking. 
- **Body:** Highly readable, accessible line-heights.

### Spacing & Layout
- Consistent Tailwind spacing scale (e.g., `p-4`, `m-6`, `gap-4`).
- Containers use `max-w-7xl` with responsive padding (`px-4 sm:px-6 lg:px-8`).

### Color Palette (Abstract)
- **Primary Brand:** A distinct accent color (e.g., deep blue or vibrant violet) used sparingly for primary actions (buttons, active states).
- **Light Mode (Dominant):** White/off-white backgrounds (`bg-white`, `bg-slate-50`), dark slate text (`text-slate-900`), subtle borders (`border-slate-200`).
- **Dark Mode:** Deep dark backgrounds (`dark:bg-slate-900`, `dark:bg-slate-800`), light text (`dark:text-slate-100`), subdued borders (`dark:border-slate-700`).

### Responsive Breakpoints
- `sm`: 640px (Large Phones/Small Tablets)
- `md`: 768px (Tablets)
- `lg`: 1024px (Desktops)
- `xl`: 1280px (Large Desktops)
- `2xl`: 1536px (Ultrawide)

## Blade Components

### Core Components
- **`x-button`**: Variants (primary, secondary, danger, outline, ghost). Handles loading states and icon slots.
- **`x-card`**: Base container with appropriate padding, border-radius, and subtle shadows.
- **`x-badge`**: Small status indicators (e.g., "HD", "Completed", rating pills).
- **`x-modal`**: Alpine.js powered modal for dialogs and quick actions.
- **`x-form.*`**: Reusable inputs (`x-form.input`, `x-form.select`, `x-form.textarea`) with built-in error message handling.

### Domain Components
- **`x-content-card`**: Reusable poster card for a movie/series. Includes poster image, title, year, type badge, and hover overlay.
- **`x-episode-card`**: Landscape card for episodes showing thumbnail, episode number, title, and duration.
- **`x-section-header`**: Consistent header for sections like "Trending Movies", "Latest Episodes", with optional "View All" link.
- **`x-rating`**: Star rating component.

## Major Public Pages

### 1. Homepage (`/`)
- Hero Banner (Carousel of featured content).
- Sections: Trending Movies, Latest Anime Episodes, Popular Series.
- Clean footer.

### 2. Search & Browse (`/browse`, `/search`)
- Advanced filtering (Genre, Type, Year, Status).
- Responsive grid of `x-content-card`.
- Pagination.

### 3. Content Detail (`/content/{slug}`)
- Backdrop hero image with gradient overlay.
- Poster, Title, Metadata (Release Date, Duration, Rating, Genres).
- Description/Synopsis.
- **For Movies:** "Watch Now" button, Server selection, Download links.
- **For Series/Anime:** Season selector, list of `x-episode-card`s.

### 4. Watch Page (`/watch/{content}/{episode?}`)
- Focused, theater-like layout (darker background even in light mode).
- Main Video Player area.
- Server/Provider selection tabs.
- Media controls, light dimmer.
- Below Player: Content metadata, episode navigation, download links.

## Admin UI
- Sidebar navigation.
- Data tables with search, sort, and pagination.
- Forms for creating/editing content, seasons, and episodes.
- Source management UI (adding video URLs, managing server priorities).
- Follows the same color tokens but optimized for data density.
