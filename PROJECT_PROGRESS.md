# PROJECT_PROGRESS — TASKGROUP

Project: TaskGroup — Sistem Manajemen Tugas & Monitoring Progres Kelompok
Stack: Laravel 12, PHP 8.3+ (dijalankan di PHP 8.5), MySQL (dev) / SQLite (test), Blade + Tailwind CSS 4 + Alpine.js + Vite.
Auth: Laravel Authentication + Google OAuth (Socialite).

Dokumen ini adalah catatan progres sesi. Spec utama: `TASKGROUP_HERMES_PROMPT.md`.

---

## STATUS PER PHASE

### PHASE 1 — Database, nomor absen, Google login: COMPLETED
- Migration baru: `2026_11_06_000001_add_attendance_number_to_users_table.php` (nullable, unique `(class_id, attendance_number)`).
- `app/Support/AttendanceNumber.php`: normalisasi "03" -> 3, rules unik per kelas, label 2 digit.
- `User`: fillable/cast `attendance_number`, `attendanceLabel()`, `needsClassProfile()`.
- `GoogleAuthController`: setelah login Google, siswa yang belum lengkap class/absen diarahkan ke `profile.edit`.
- `EnsureStudentHasClass`: siswa lama (class_id ada, absen belum) diarahkan ke profil.
- `ProfileController` + `UpdateProfileRequest`: simpan class_id & attendance_number, tangani race condition unique.
- Admin: `StudentController` + `Store/UpdateStudentRequest` + view create/edit/index menampilkan Absen.
- Nomor absen tampil di: profil, daftar siswa, anggota kelompok (admin & guru), detail individu.
- Test: `AttendanceNumberTest` (11 lolos), `AuthenticationTest` (4 lolos).

### PHASE 2 — Manajemen kelompok Guru + pembatasan kelas: COMPLETED
- Migration baru: `2026_11_06_000002_create_teacher_classes_table.php` (pivot guru <-> kelas).
- `User::teachingClasses()/teachingClassIds()`, `SchoolClass::teachers()`.
- Admin: `ClassController` + request + view menambah multi-select "Guru Pengampu".
- `app/Http/Controllers/Guru/GroupController.php` (index/create/store/edit/update/destroy/setLeader/addMember/removeMember) dibatasi `teacher_classes`; akses di luar kewenangan -> 404.
- `app/Http/Requests/Guru/{StoreGroupRequest,UpdateGroupRequest,StoreGroupMemberRequest}.php`.
- View `resources/views/guru/groups/{index,create,edit}.blade.php`; menu Kelompok guru muncul di sidebar.
- Test: `GuruGroupManagementTest` (12 lolos).

### PHASE 3 — Upload 5 MB + format luas: COMPLETED
- `app/Support/UploadRules.php`: 5 MB, 44 ekstensi, MIME permisif, label, accept attribute.
- Register rule `extensions` di `AppServiceProvider` (sebelumnya dipakai tapi belum terdaftar).
- `UploadTaskFileRequest` + `Siswa\TaskController::submitSubtaskProof` memakai `UploadRules`.
- Executable (exe/bat/sh/phar) ditolak via allowlist.
- Frontend: validasi Alpine (ukuran + ekstensi) di form dokumen & bukti; teks "Maks 5 MB".
- Test: `FileTest` (7 lolos).

### PHASE 4 — Alur penyelesaian tugas & penilaian: COMPLETED
- `Siswa\TaskController::submitTask`: tugas tanpa checklist bisa dikumpulkan.
- `Guru\TaskGroupController::updateIndividualAssessment`: tidak mewajibkan progress=100.
- Migration baru: `2026_11_06_000003_backfill_submitted_task_groups.php` (data lama progress 100% in_progress -> submitted).
- Test suite disesuaikan dengan alur nyata + syarat Phase 1: `TaskTest`, `AuthorizationTest`, `ProgressTest`, `AssessmentTest`.
- Test: semua lolos.

### PHASE 5 — Export XLSX & PDF: COMPLETED
- Dependency baru: `phpoffice/phpspreadsheet ^5.10`, `dompdf/dompdf ^3.1`.
- `app/Services/ReportExportService.php`: buildRows (kelompok + anggota terurut absen), XLSX (style, border, auto-width, absen dipaksa string 2 digit), PDF landscape (nomor halaman + tanggal export).
- `resources/views/reports/export-pdf.blade.php`.
- `Guru\ReportController` & `Admin\ReportController`: `export` -> XLSX, `print` -> PDF asli.
- View laporan: tombol "Export Excel (XLSX)" & "Export PDF".
- Test: `ReportExportTest` (4 lolos), `MonitoringAndReportTest` (5 lolos).

### PHASE 6 — Leaderboard individual & kelompok per kelas: COMPLETED
- `app/Http/Controllers/Siswa/LeaderboardController.php` + `resources/views/siswa/leaderboard.blade.php`.
- Route `siswa.leaderboard.index` (muncul di sidebar siswa).
- Tab Individual & Kelompok; dibatasi `class_id` siswa login.
- Poin kelompok = nilai tugas kelompok + nilai tugas individu anggota.
- Poin individu = nilai tugas individu + bagian adil nilai tugas kelompok (dibagi jumlah anggota).
- Hanya tugas `completed` dengan `score` valid yang dihitung.
- Podium top 3, medali, highlight siswa login, daftar anggota + nomor absen, responsif.
- Test: `LeaderboardTest` (5 lolos).

### PHASE 7 — Redesign UI/UX modern tech: COMPLETED
- Design system di `resources/css/app.css` (panel-card, stat-card, hero-card, sidebar-surface, palet status, animasi).
- Palet `blue-*` dipetakan ke indigo; markup kartu lama dipetakan otomatis ke gaya baru.
- Komponen Blade: alert, badge, card, danger-button, dropdown, empty-state, input, label, modal, nav-link, page-header, primary-button, progress-bar, secondary-button, select, textarea, notification-dropdown, comment-thread, deadline-badge.
- Sidebar drawer (mobile) + sidebar permanen (desktop), topbar dengan notifikasi & menu profil.
- Halaman yang disegarkan pada sesi ini: notifikasi, laporan guru, leaderboard, plus perbaikan bertahap sebelumnya (login, dashboards, manajemen, tugas, penilaian, monitoring).
- Build assets: `npm run build` berhasil (`public/build/assets/app-C--0HV3T.css`, `app-D8c_vgOE.js`).

### PHASE 8 — Testing & verifikasi final: COMPLETED
- `php artisan test`: **79 passed, 0 failed** (313 assertions).
- `php artisan view:cache`: semua Blade view terkompilasi.
- `php artisan route:list`: semua route terdaftar tanpa "Class not found".
- `php artisan migrate --force`: semua migration jalan (termasuk backfill).
- Verifikasi HTTP end-to-end (login admin/guru/siswa + semua halaman utama): HTTP 200.
- Verifikasi export: XLSX diawali `PK` dan berisi kelompok/anggota/absen; PDF diawali `%PDF` dan berisi kelompok/anggota/nomor absen.

---

## MIGRATION BARU (sesi ini)
1. `2026_11_06_000001_add_attendance_number_to_users_table.php`
2. `2026_11_06_000002_create_teacher_classes_table.php`
3. `2026_11_06_000003_backfill_submitted_task_groups.php`

## DEPENDENCY BARU
- `phpoffice/phpspreadsheet ^5.10`
- `dompdf/dompdf ^3.1`
- Environment: `php-gd` (Termux) untuk test upload gambar.

## FILE BARU (pengembangan)
- `app/Support/AttendanceNumber.php`
- `app/Support/UploadRules.php`
- `app/Services/ReportExportService.php`
- `app/Http/Controllers/Guru/GroupController.php`
- `app/Http/Controllers/Siswa/LeaderboardController.php`
- `app/Http/Requests/Guru/{StoreGroupRequest,UpdateGroupRequest,StoreGroupMemberRequest}.php`
- `resources/views/guru/groups/{index,create,edit}.blade.php`
- `resources/views/siswa/leaderboard.blade.php`
- `resources/views/reports/export-pdf.blade.php`
- Tests: `GuruGroupManagementTest`, `LeaderboardTest`, `ReportExportTest` (+ FileTest & lainnya diperbarui)

## PERUBAHAN PENTING
- `app/Providers/AppServiceProvider.php`: register rule `extensions`.
- `app/Http/Controllers/Admin/ClassController.php` + request + view: guru pengampu.
- `app/Http/Controllers/Guru/ReportController.php` & `Admin\ReportController.php`: export XLSX/PDF.
- `app/Http/Controllers/Siswa/TaskController.php`: submit tanpa checklist + aturan upload.
- `app/Http/Controllers/Guru/TaskGroupController.php`: penilaian individu.
- `routes/web.php`: route guru groups + siswa leaderboard.
- `config/database.php`: fix deprecation PHP 8.5 (`Pdo\Mysql::ATTR_SSL_CA` bersyarat).

## KREDENSIAL DEMO (database dev lokal)
- Admin: `adminprima@gmail.com` / `prima123`
- Guru: `guru@test.id` / `guru123` (mengampu X RPL 1)
- Siswa: `siswa@test.id` / `siswa123` (X RPL 1, absen 01, Kelompok 1)

## CATATAN / KNOWN ISSUES
- Test menggunakan SQLite in-memory (`phpunit.xml`); migrasi remaining di MySQL dev sudah dijalankan.
- `storage/app/temp/` dipakai export XLSX sementara (sudah di-ignore).
- Artefak test `storage/framework/testing/` muncul saat test (sudah di-ignore).
- File `storage/framework/testing/disks/local/profile-photos/{8,9}/*.png` yang sebelumnya ter-track jadi terhapus oleh test; aman, bukan data aplikasi.

## PENGHAPUSAN FITUR (sesi lanjutan)

Fitur prioritas tugas dihapus dari seluruh role (admin, guru, siswa):
- Migration baru: `2026_11_06_000004_drop_priority_from_tasks_table.php` (drop kolom `priority`).
- `Task`: konstanta PRIORITY_*, fillable, `priorityLabel()`, `priorityColor()` dihapus.
- `StoreTaskRequest` & `UpdateTaskRequest`: validasi & atribut prioritas dihapus.
- `Guru\TaskController` & `Guru\MonitoringController`: filter & field prioritas dihapus.
- View: form prioritas (create/edit), filter (tasks index & monitoring), badge (tasks show, monitoring, reports guru/admin) dihapus.
- `TaskFactory` & test (`TaskTest`, `TaskRecipientClassFilterTest`) dibersihkan.
- Kolom `priority` di database dev sudah di-drop (dicek via Schema::hasColumn).
- Verifikasi: 79 test lolos, view:cache OK.

## NEXT STEPS (jika ada sesi lanjutan)
- Tidak ada fitur yang tertinggal dari 7 permintaan utama.
- Opsional: seed data demo yang lebih kaya; audit performa (N+1) pada halaman monitoring/laporan; email/notifikasi.

---

## VISUAL EFFECTS (sesi lanjutan)

Tiga efek diminta dan sudah ditambahkan tanpa mengubah struktur markup:

1. Button Animation (`btn-animate`)
   - Lift (`translateY(-2px)`) saat hover, press (`translateY(0) scale(.97)`) saat aktif.
   - Kilau (sheen sweep) putih saat hover (`@keyframes btn-sheen`).
   - Diterapkan ke komponen `x-primary-button`, `x-secondary-button`, `x-danger-button`.

2. Card Hover
   - `.panel-card` hover: `-translate-y-1` + border indigo + shadow hover.
   - `.stat-card` mewarisi efek panel-card.
   - Utility `card-hover` tersedia untuk elemen kartu lain.
   - Markup kartu lama (`.rounded-2xl.border.bg-white`, `.rounded-xl.border.border-gray-200.bg-white`) ikut terangkat 2px saat hover.

3. Skeleton Shimmer
   - Utility `.skeleton` (gradient bergerak, `@keyframes skeleton-shimmer`), `.skeleton-text`, `.skeleton-circle`.
   - Komponen `x-skeleton` (`resources/views/components/skeleton.blade.php`).
   - Loading state dengan skeleton diterapkan di dashboard admin & siswa (700ms, Alpine `x-data`/`x-show` + `x-cloak` sebagai fallback).
   - Aman tanpa JS: konten tetap terlihat (skeleton tersembunyi lewat CSS `[x-cloak]`).

Build assets: `public/build/assets/app-D_ufxbqB.css`, `app-D8c_vgOE.js`.
Verifikasi: view:cache OK, 79 test lolos, halaman admin/siswa berisi `btn-animate` & `skeleton`.

