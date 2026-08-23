# NontonKu

NontonKu is a modern, responsive, and robust media streaming platform (Movies, Series, Anime, Donghua) built with Laravel 11. It features a custom HTML5 video player, advanced filtering, personalized recommendations, and a comprehensive admin dashboard.

## Features

- **Multi-Type Content Support:** Seamlessly manage and stream Movies, Series, Anime, and Donghua from a unified architecture.
- **Custom Video Player:** Integrated HTML5 player using Alpine.js and Tailwind CSS, with native support for MP4, HLS (.m3u8), and Embed (iframe) sources.
- **Advanced Discovery:** Robust search and filtering (keyword, type, genre, status, sorting) along with dedicated genre discovery pages.
- **Personalized Recommendations:** "Because You Watched" and "Popular" algorithms powered by user watch history and ratings.
- **User Engagement:** Watchlist, Favorites, 1-5 star Ratings, and Watch History (with "Continue Watching" progress sync).
- **Admin Dashboard:** Comprehensive content management system for managing seasons, episodes, video sources, genres, and users.
- **Dark & Light Mode:** First-class dark mode support across all public and admin views.
- **SEO Optimized:** Dynamic Open Graph tags, canonical URLs, and JSON-LD structured data.

## Tech Stack

- **Backend:** [Laravel 11](https://laravel.com), PHP 8.2+, MySQL
- **Frontend:** Blade Templates, [Tailwind CSS](https://tailwindcss.com) v3, [Alpine.js](https://alpinejs.dev)
- **Asset Compilation:** Vite
- **Testing:** Pest PHP (100% Test Pass Rate across 127+ tests)
- **Video Playback:** Native HTML5, [HLS.js](https://github.com/video-dev/hls.js/)

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0+

## Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd NontonKu
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install frontend dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Configure your database credentials in the `.env` file.*

5. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

6. **Link Storage:**
   ```bash
   php artisan storage:link
   ```

7. **Compile Assets:**
   ```bash
   npm run build
   # Or for development: npm run dev
   ```

8. **Start the Development Server:**
   ```bash
   php artisan serve
   ```
   *Visit `http://localhost:8000` in your browser.*

## Documentation

For AI Agents and developers, please refer to the comprehensive internal documentation:
- [AI Handover & Project State](docs/AI_HANDOVER.md)
- [Architecture Details](docs/ARCHITECTURE.md)
- [Database Schema](docs/DATABASE.md)

## Testing

NontonKu uses Pest PHP for testing. To run the test suite:
```bash
php artisan test
```

## License

This project is proprietary and confidential. Unauthorized copying of this project, via any medium is strictly prohibited.
