Saya sedang mengembangkan project Laravel bernama NontonKu.

PHASE 1–5 SUDAH SELESAI DAN JANGAN MERUSAK IMPLEMENTASI YANG SUDAH ADA.

Status terakhir:

php artisan test

Tests: 74 passed (170 assertions)
Duration: 3.01s

Semua test PASS.

Fitur yang sudah selesai:

- Authentication
- Registration
- Login / Logout
- Email Verification
- Password Reset
- Password Update
- Profile
- Admin Authorization
- Content Management architecture
- Genre
- Season
- Episode
- Video Source polymorphic relation
- Published content filtering
- Search
- Catalog pagination & sorting
- Favorites
- Episode Bookmark / Watchlist
- Watch History
- Continue Watching
- Ratings 1–10
- Average rating caching
- Dark / Light mode
- Public navigation
- Alpine.js + fetch() untuk favorite/bookmark
- CSRF protection
- User-specific authorization
- Comprehensive Pest tests

Phase 5 terakhir menghasilkan:

74 passed (170 assertions)

JANGAN menghapus, mengganti, atau merusak test dan fitur Phase 1–5 yang sudah PASS.

==================================================
PHASE 6 — VIDEO PLAYER & STREAMING EXPERIENCE
==================================================

Tujuan Phase 6:

Membangun pengalaman menonton video yang lengkap, modern, responsif, dan terintegrasi dengan Watch History / Continue Watching yang sudah dibuat pada Phase 5.

Sebelum melakukan perubahan:

1. Inspect seluruh struktur project terlebih dahulu.
2. Baca model Content, Season, Episode, VideoSource, User dan WatchHistory.
3. Baca route yang sudah ada.
4. Baca controller yang berhubungan dengan Content, Episode dan Watch History.
5. Baca service WatchHistoryService.
6. Baca view public/show.blade.php dan layout public.
7. Jangan membuat ulang fitur yang sudah tersedia.
8. Gunakan architecture yang sudah ada.
9. Jangan melakukan perubahan besar yang tidak diperlukan.
10. Pertahankan compatibility dengan semua test Phase 1–5.

==================================================

1. # VIDEO PLAYER

Implementasikan halaman / section video player yang modern dan responsive.

Player harus mendukung:

- Play
- Pause
- Volume
- Mute
- Seek
- Progress bar
- Current time
- Duration
- Fullscreen
- Playback speed
- Responsive mobile layout
- Loading state
- Error state
- Empty video source state

Gunakan teknologi yang sudah tersedia di project.

Jika project belum menggunakan library video player khusus, prioritaskan HTML5 video + Alpine.js daripada menambahkan dependency besar yang tidak diperlukan.

Jangan menambahkan dependency baru kecuali benar-benar diperlukan.

Player harus bekerja dengan:

- Movie
- Series
- Anime
- Donghua
- Episode

# ================================================== 2. VIDEO SOURCE

Inspect struktur VideoSource terlebih dahulu.

Jangan mengasumsikan nama kolom sebelum membaca model dan migration.

Video source harus digunakan berdasarkan struktur database yang sudah ada.

Pastikan:

- source yang tidak valid tidak menyebabkan crash
- source kosong menampilkan error state yang bagus
- source hanya dapat diakses melalui content/episode yang valid
- unpublished content tidak dapat diputar

Jangan expose data internal database yang tidak diperlukan ke frontend.

# ================================================== 3. WATCH HISTORY INTEGRATION

Integrasikan player dengan WatchHistoryService yang SUDAH ADA.

Jangan membuat service history baru jika service yang ada masih dapat digunakan.

Ketika user menonton video:

- Simpan progress secara berkala
- Jangan request terlalu sering
- Gunakan interval yang reasonable
- Simpan ketika user pause
- Simpan ketika video selesai
- Simpan ketika user meninggalkan halaman jika memungkinkan

Data yang disimpan harus menggunakan:

- progress_seconds
- duration_seconds

Gunakan endpoint WatchHistory yang sudah ada jika memungkinkan.

Jangan membuat duplicate endpoint tanpa alasan.

# ================================================== 4. RESUME PLAYBACK

Jika user memiliki watch history untuk video tersebut:

Saat player dibuka:

- Ambil progress terakhir
- Resume video dari posisi tersebut
- Jangan resume jika progress sudah dianggap completed
- Jangan menyebabkan video autoplay jika browser memblokir autoplay

Jika progress:

0 < progress < duration

maka tampilkan opsi:

"Continue watching from 12:35"

atau secara otomatis seek ke posisi tersebut jika UX existing memang mendukung.

Pastikan browser autoplay policy tidak dilanggar.

# ================================================== 5. AUTO COMPLETE

Ketika video hampir selesai:

- Tandai sebagai completed menggunakan logic WatchHistoryService yang sudah ada.
- Jangan menyimpan progress melebihi duration.
- Gunakan logic existing untuk menentukan completed.

Jangan membuat logic completion berbeda jika service existing sudah menangani hal tersebut.

# ================================================== 6. EPISODE NAVIGATION

Untuk Series / Anime / Donghua:

Tambahkan:

- Previous Episode
- Next Episode
- Episode List
- Current Episode indicator

Contoh:

Season 1

Episode 1
Episode 2
Episode 3
Episode 4

Episode aktif harus terlihat jelas.

Jika episode pertama:

Previous Episode disabled.

Jika episode terakhir:

Next Episode disabled.

# ================================================== 7. AUTO NEXT EPISODE

Ketika video selesai:

Jika terdapat episode berikutnya:

Tampilkan UI:

"Episode berikutnya"

dengan countdown atau tombol:

"Next Episode"

Jangan langsung redirect tanpa memberikan UX yang baik.

Jika memungkinkan:

- countdown 5 detik
- tombol Cancel
- tombol Next Episode

Namun jangan membuat UX terlalu kompleks.

Untuk movie:

Tidak ada auto-next episode.

# ================================================== 8. CONTINUE WATCHING

Integrasikan dengan fitur Continue Watching Phase 5.

Pastikan:

Homepage tetap menampilkan:

Continue Watching

dengan:

- poster
- title
- episode jika ada
- progress bar
- percentage
- resume action

Ketika user mengklik item:

User diarahkan ke video yang benar.

Untuk episode:

Arahkan ke episode terakhir yang sedang ditonton.

Jangan mengubah behavior Continue Watching yang sudah PASS kecuali diperlukan untuk integrasi player.

# ================================================== 9. PLAYER UI

Buat UI modern yang cocok dengan NontonKu.

Gunakan:

- Tailwind CSS
- Alpine.js
- Existing design system
- Existing dark/light mode

Player harus memiliki:

Dark mode:

- background gelap
- controls jelas
- text readable

Light mode:

- tetap readable
- tidak merusak layout

Jangan membuat UI terlalu ramai.

Prioritaskan pengalaman seperti platform streaming modern.

# ================================================== 10. RESPONSIVE

Pastikan player bekerja pada:

- Desktop
- Laptop
- Tablet
- Mobile

Mobile harus:

- player full width
- controls mudah disentuh
- episode list tidak overflow
- tidak ada horizontal scrolling

# ================================================== 11. SECURITY

Pastikan:

- Guest hanya dapat menonton content yang memang public/published.
- User tidak dapat mengakses unpublished content.
- User tidak dapat mengubah watch history user lain.
- Watch history selalu menggunakan authenticated user.
- Episode harus benar-benar berasal dari content yang diizinkan.
- Jangan menerima user_id dari request.
- Gunakan Auth::id() / authenticated user.
- Validasi semua route model binding.
- Jangan trust ID dari frontend tanpa authorization.

# ================================================== 12. PERFORMANCE

Hindari:

- N+1 queries
- query berulang untuk episode
- query berulang untuk watch history
- loading seluruh season jika tidak diperlukan

Gunakan eager loading jika memang diperlukan:

with()

load()

dan query yang efisien.

Jangan melakukan eager loading berlebihan.

# ================================================== 13. TESTING

Tambahkan Pest tests untuk Phase 6.

Minimal:

tests/Feature/User/VideoPlayerTest.php

tests/Feature/User/WatchProgressTest.php

tests/Feature/User/EpisodeNavigationTest.php

tests/Feature/User/VideoAuthorizationTest.php

Tests minimal harus mencakup:

1. Guest dapat mengakses published content.
2. Guest tidak dapat mengakses unpublished content.
3. User dapat mengakses episode yang valid.
4. User tidak dapat mengakses episode dari content yang tidak dipublikasikan.
5. User dapat menyimpan progress.
6. Progress tidak boleh melebihi duration.
7. Progress user tidak bocor ke user lain.
8. Continue Watching menggunakan progress yang benar.
9. Completed video tidak muncul sebagai Continue Watching.
10. Episode navigation memilih episode yang benar.
11. Previous episode bekerja.
12. Next episode bekerja.
13. Episode terakhir tidak memiliki next episode.
14. Invalid episode/content menghasilkan response yang benar.
15. Video source kosong tidak menyebabkan application error.

Jangan menghapus test lama.

# ================================================== 14. ROUTING

Inspect route yang sudah ada terlebih dahulu.

Gunakan route naming yang konsisten dengan project.

Contoh jika diperlukan:

/watch/{content}/{episode?}

atau struktur route yang paling sesuai dengan existing architecture.

Jangan membuat route duplicate.

Setelah selesai jalankan:

php artisan route:list

dan pastikan tidak ada collision.

# ================================================== 15. VALIDATION

Setelah implementasi selesai:

1. php artisan optimize:clear
2. php artisan route:list
3. php artisan test

SEMUA TEST PHASE 1–5 HARUS TETAP PASS.

Target:

Existing:
74 passed

Ditambah test Phase 6.

Tidak boleh ada regression.

# ================================================== 16. BROWSER VERIFICATION

Setelah automated tests PASS, lakukan browser verification.

Periksa:

- Homepage
- Continue Watching
- Content detail
- Movie player
- Series episode player
- Episode navigation
- Next episode
- Previous episode
- Watch history
- Dark mode
- Light mode
- Mobile responsive
- Guest access
- Authenticated access

Pastikan tidak ada:

- console error
- broken link
- 404
- 500
- JavaScript error
- Alpine error
- layout overflow
- broken dark/light mode

# ================================================== 17. IMPORTANT DEVELOPMENT RULES

JANGAN:

- Menghapus fitur lama.
- Menghapus test lama.
- Mengubah database tanpa alasan.
- Membuat duplicate service.
- Membuat duplicate endpoint.
- Mengubah authentication architecture.
- Mengubah authorization yang sudah bekerja.
- Mengubah Dark/Light mode yang sudah berhasil.
- Menambahkan dependency besar tanpa alasan.
- Membuat hardcoded user_id.
- Membypass authorization.
- Menggunakan raw SQL jika Eloquent/query builder sudah cukup.
- Mengabaikan existing service WatchHistoryService.

SEBELUM coding:

Inspect project terlebih dahulu.

SETELAH coding:

Run tests.

Jika menemukan masalah:

Perbaiki akar masalahnya, bukan dengan menonaktifkan atau menghapus test.

==================================================
FINAL REPORT
==================================================

Setelah Phase 6 selesai, berikan laporan:

1. Files yang dibuat.
2. Files yang diubah.
3. Route baru.
4. Feature baru.
5. Database changes jika ada.
6. Test baru.
7. Jumlah total test.
8. Jumlah assertions.
9. Hasil php artisan test.
10. Hasil php artisan route:list.
11. Browser verification.
12. Masalah yang ditemukan dan bagaimana diperbaiki.
13. Apakah Phase 1–5 tetap kompatibel.
14. Rekomendasi Phase 7.

JANGAN menyatakan Phase 6 selesai sebelum:

php artisan test

benar-benar PASS.

Target akhir:

0 failed
0 errors
0 regressions
