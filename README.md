# TaskGroup

Sistem Manajemen Tugas & Monitoring Progres Kelompok — Laravel 12 + Blade + Tailwind CSS + Alpine.js.

## Status pengerjaan

Progres saat ini: **Tahap 1–11 dari 21 selesai**.

- Tahap 1: Setup project — selesai
- Tahap 2: Migration & database schema — selesai
- Tahap 3: Models & relationships — selesai
- Tahap 4: Factories & seeders — selesai (kecuali `GroupMemberFactory`, dilengkapi di Tahap 10)
- Tahap 5: Authentication — selesai
- Tahap 6: Middleware role — selesai
- Tahap 7: Policies — sebagian (`TaskPolicy`; `GroupPolicy`, `CommentPolicy`, `TaskFilePolicy` menyusul saat fitur terkait dibangun)
- Tahap 8: Admin dashboard & CRUD (guru, siswa, kelas) — selesai
- Tahap 9: Guru dashboard & task management — selesai
- Tahap 10: Kelompok & anggota kelompok (admin) — selesai
- Tahap 11: Siswa dashboard — selesai

Tahap berikutnya: **Tahap 12 — Subtask/checklist** (halaman detail tugas
untuk siswa: menandai subtask selesai, yang nantinya memicu perhitungan
progress otomatis pada Tahap 13).

## Cara menjalankan

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
php artisan migrate --seed
npm run build
php artisan serve
```

Seeder hanya membuat satu akun administrator awal. Data guru, siswa, kelas,
kelompok, dan tugas dibuat sendiri melalui aplikasi.

| Role          | Email                | Password |
| ------------- | -------------------- | -------- |
| Administrator | adminprima@gmail.com | prima123 |

## Login Google

Login Google membuat akun siswa otomatis untuk email Google yang sudah
terverifikasi. Jika email Google sudah terdaftar, akun yang ada akan ditautkan
dan role yang sudah diberikan admin tetap dipertahankan. Guru dan administrator
tetap dibuat/dikelola oleh administrator.

Untuk mengaktifkan OAuth, buat OAuth Client ID bertipe **Web application** di
Google Cloud Console. Tambahkan `http://localhost:8000` sebagai Authorized
JavaScript origin dan `http://localhost:8000/auth/google/callback` sebagai
Authorized redirect URI, lalu isi `.env`:

```dotenv
GOOGLE_CLIENT_ID=client-id-dari-google
GOOGLE_CLIENT_SECRET=client-secret-dari-google
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

Jalankan `php artisan migrate` untuk menambahkan kolom penghubung akun Google,
kemudian `php artisan config:clear`. Untuk domain/host lain, samakan redirect URI
di Google Cloud Console dengan `GOOGLE_REDIRECT_URI`.

## Catatan penting

`vendor/` tidak disertakan — jalankan `composer install` untuk mengunduh
dependency Laravel framework setelah download project ini. Begitu juga
`node_modules/` — jalankan `npm install`.
