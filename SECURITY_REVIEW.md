# Tahap 21 — Final Security Review

Review ini dilakukan terhadap seluruh kode yang dibangun pada Tahap 1-20,
menyisir tiap poin checklist keamanan pada spesifikasi bagian 25. Ditulis
per area, dengan status masing-masing.

## 1. CSRF Protection ✅
Tidak ada override pada `VerifyCsrfToken`/`bootstrap/app.php` yang
mengecualikan route manapun dari proteksi CSRF bawaan Laravel 12 untuk
grup middleware `web`. Semua form (`comment-thread`, `create`/`edit`
tugas, upload file, dsb.) memakai `@csrf`.

## 2. Password Hashing ✅
Seluruh penyimpanan password memakai `Hash::make()` (`TeacherController`,
`StudentController`, `ProfileController`, seeder). Login memakai
`Auth::attempt()` (bcrypt-safe, timing-safe), bukan perbandingan manual.
Login juga sudah dilindungi **rate limiting** (5 percobaan lalu
lockout progresif) di `LoginRequest` — ini melebihi requirement dasar
spesifikasi, sengaja dipertahankan.

## 3. Authorization (Middleware + Policy + Gate) ✅
- Middleware `role:` (`EnsureUserHasRole`) menjaga level route per
  prefix (`/admin`, `/guru`, `/siswa`).
- Policy (`TaskPolicy`, `CommentPolicy`, `TaskFilePolicy`) menjaga level
  record: guru hanya bisa mengubah tugas miliknya sendiri, siswa hanya
  bisa melihat tugas kelompoknya, dst. Semua policy punya `before()`
  yang memberi admin akses penuh secara eksplisit.
- Dikonfirmasi TIDAK ada satupun endpoint yang hanya mengandalkan
  penyembunyian tombol di Blade tanpa pengecekan authorize() di
  belakangnya (spesifikasi: "Jangan hanya menyembunyikan tombol
  berdasarkan role").

## 4. Form Request Validation ✅
Setiap endpoint tulis (`store`/`update`) memakai Form Request khusus
(bukan `Request` generik), dengan `rules()` eksplisit. Tidak ditemukan
satupun `Model::create($request->all())` di seluruh controller —
semua memakai `$request->validated()`/`$request->safe()->only([...])`.

## 5. Mass Assignment Protection ✅
Semua model memakai `$fillable` (whitelist), bukan `$guarded = []`.
Field sensitif (`role` pada `User`, `score`/`status` pada `TaskGroup`)
dikonfirmasi HANYA pernah ditulis dari satu jalur terkontrol:
- `role` → hanya di-hardcode oleh `Admin\TeacherController`/
  `StudentController`, tidak pernah dari input form.
- `score` → hanya dari `Guru\AssessmentController` (dijaga
  `TaskPolicy::update`), tidak ada jalur siswa yang menyentuhnya.
- `status` pada `task_groups` → hanya diturunkan otomatis lewat
  `ProgressService`/`AssessmentController`, tidak pernah dari input
  form langsung (spesifikasi bagian 16).

## 6. IDOR (Insecure Direct Object Reference) ✅
Diperiksa satu per satu endpoint yang menerima ID di URL:
- `Siswa\TaskController`, `FileController`, `CommentController`:
  kelompok SELALU diselesaikan dari kelompok aktif user yang login,
  tidak pernah dipercaya dari input — lalu divalidasi ulang bahwa
  tugas benar-benar diberikan ke kelompok tsb (`resolveTaskGroup()`).
- `FileDownloadController`: `authorize('view', $file)` mengecek
  keanggotaan kelompok/kepemilikan tugas sebelum file di-stream.
- `NotificationController`: `authorizeOwnership()` mencocokkan
  `notifiable_id`/`notifiable_type` dengan user yang login.
- `Admin\GroupMemberController::destroy`: memverifikasi
  `$member->group_id === $group->id` sebelum menghapus.

## 7. Secure File Upload ✅
- Validasi MIME **dan** extension sekaligus (`mimes:` rule menolak file
  berekstensi dipalsukan), batas ukuran 10 MB (`UploadTaskFileRequest`).
- Nama file di disk memakai UUID acak (`FileUploadService`), nama asli
  hanya disimpan sebagai metadata (`file_name`) — tidak pernah dipakai
  untuk path fisik (mencegah path traversal/overwrite).
- Disk `task_files` bersifat privat (bukan `public`), sehingga file
  tidak bisa diakses lewat URL langsung — hanya lewat
  `FileDownloadController` yang menjalankan Policy.

## 8. Business-rule enforcement (bukan cuma di form) ✅
Aturan "satu siswa maksimal satu kelompok aktif per kelas" ditegakkan
di `GroupMembershipService`, dipakai oleh `StoreGroupMemberRequest`
sebagai validasi — bukan hanya di level tampilan.

## 9. Temuan yang diperbaiki pada tahap ini 🔧
Satu inkonsistensi ditemukan pada desain cascade foreign key
(spesifikasi bagian 36): `task_comments.user_id` semula memakai
`cascadeOnDelete()`, berbeda dari pola yang konsisten dipakai di
`task_activities`/`task_files` (`nullOnDelete()`). Efeknya: jika akun
seorang siswa/guru dihapus, SELURUH komentarnya di semua thread tugas
ikut terhapus permanen — bukan cuma kehilangan atribusi penulis,
melainkan hilangnya jejak diskusi (bertentangan dengan spesifikasi
bagian 21: "Semua aktivitas penting dicatat").

**Perbaikan**: migration `task_comments` diubah menjadi
`user_id` nullable + `nullOnDelete()`, konsisten dengan tabel riwayat
lainnya. View (`comment-thread.blade.php`) dan
`NotificationService::taskComment()` disesuaikan agar null-safe
(`$comment->user?->name ?? 'Pengguna telah dihapus'`).

## 10. Hal yang TIDAK diubah, tapi perlu jadi perhatian sebelum production
Ini bukan bug di kode, melainkan konfigurasi environment yang wajar
untuk development tapi harus diganti sebelum aplikasi benar-benar
dipakai sekolah:
- `.env`: `APP_DEBUG=true` → set ke `false` di server produksi (mode
  debug membocorkan stack trace lengkap, termasuk isi `.env`, ke
  pengguna saat terjadi error).
- `.env`: `APP_KEY` bawaan di file ini dipakai bersama oleh semua
  yang meng-clone zip ini → jalankan `php artisan key:generate` sekali
  di server produksi supaya kunci enkripsi sesi/cookie unik.
- Password akun demo (`admin@example.com`, dst.) pada seeder memakai
  password sederhana untuk kemudahan development (spesifikasi bagian
  33 memang meminta ini secara eksplisit) — wajib diganti sebelum
  dipakai sungguhan.
- Ekspor laporan memakai CSV + halaman cetak browser (bukan
  `barryvdh/laravel-dompdf`/`maatwebsite/excel`) karena keterbatasan
  akses jaringan saat pengerjaan Tahap 19 — fungsinya sudah terpenuhi,
  tapi kalau ingin file PDF/XLSX asli, tinggal pasang paket tsb dan
  ganti isi `ReportController::print()`/`export()`.

## Kesimpulan
Dari seluruh checklist bagian 25, hanya **satu** temuan nyata (cascade
komentar) dan sudah diperbaiki pada tahap ini. Sisanya sudah tertangani
dengan baik sejak tahap-tahap sebelumnya (rate limiting login bahkan
melebihi requirement dasar). Aplikasi sudah dapat dianggap **100%**
mengikuti alur 21 tahap pada spesifikasi awal.
