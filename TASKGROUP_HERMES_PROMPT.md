MASTER DEVELOPMENT PROMPT — TASKGROUP

PROJECT: PENGEMBANGAN FITUR DAN REDESIGN TASKGROUP

Kamu bertindak sebagai Senior Laravel Developer, Backend Engineer, Database Engineer, dan UI/UX Designer profesional.

Saya memberikan project Laravel bernama "taksgroup.zip".

Tugas kamu adalah MELANJUTKAN, MEMPERBAIKI, dan MENYEMPURNAKAN project yang sudah ada berdasarkan permintaan berikut.

PENTING: JANGAN MEMBUAT PROJECT BARU. JANGAN MENGHAPUS FITUR YANG SUDAH ADA.

Project menggunakan:

- Laravel 12
- PHP
- MySQL / SQLite untuk pengembangan
- Blade Template
- Tailwind CSS
- Alpine.js
- Vite
- Laravel Authentication
- Google OAuth menggunakan Socialite

Role aplikasi:

1. Admin
2. Guru
3. Siswa

Pertahankan arsitektur Laravel yang sudah digunakan. Jangan mengganti teknologi tanpa alasan teknis yang kuat.

---

BAGIAN 1 — GURU DAPAT MEMBUAT DAN MENGELOLA KELOMPOK

Kondisi sekarang

Fitur CRUD kelompok hanya tersedia pada bagian Admin.

Perubahan yang diminta

Tambahkan fitur manajemen kelompok pada dashboard Guru.

Guru harus bisa:

- Melihat daftar kelompok.
- Membuat kelompok baru.
- Mengedit nama kelompok.
- Menghapus kelompok dengan validasi keamanan.
- Menambahkan anggota kelompok.
- Menghapus anggota kelompok.
- Menentukan ketua kelompok.
- Melihat jumlah anggota.
- Melihat kelas masing-masing kelompok.

Ketentuan:

1. Admin tetap memiliki akses untuk mengelola seluruh kelompok.
2. Guru dapat mengelola kelompok sesuai kelas yang menjadi tanggung jawabnya.
3. Guru tidak boleh mengakses atau mengubah kelompok di luar kewenangannya.
4. Seorang siswa tidak boleh tergabung dalam dua kelompok aktif pada kelas yang sama.
5. Validasi keanggotaan harus tetap menggunakan GroupMembershipService atau mekanisme yang setara.
6. Jangan sampai perubahan ini merusak hubungan tabel groups, group_members, dan task_groups.

Buat halaman manajemen kelompok Guru dengan desain yang konsisten dengan dashboard Guru.

---

BAGIAN 2 — FORM NOMOR ABSEN SETELAH LOGIN GOOGLE

Kondisi sekarang

Setelah siswa login menggunakan akun Google, siswa diarahkan ke halaman profil untuk memilih kelas.

Perubahan yang diminta

Tambahkan satu input baru bernama NOMOR ABSEN.

Form tersebut harus berisi:

- Pilihan kelas.
- Nomor absen.

Contoh:

Kelas: X RPL 1
Nomor Absen: 03

Tombol: Simpan dan Lanjutkan.

Ketentuan:

1. Nomor absen wajib diisi.
2. Nomor absen hanya menerima angka positif.
3. Nomor absen ditampilkan dengan format dua digit, misalnya 03, 09, 12.
4. Simpan nomor absen ke database users.
5. Buat migration baru, jangan mengubah migration lama yang sudah pernah dijalankan.
6. Nomor absen harus ditampilkan di halaman profil, daftar siswa, daftar anggota kelompok, laporan, dan export.
7. Nomor absen harus unik dalam satu kelas, bukan harus unik secara global.
8. Admin dapat melihat dan mengelola nomor absen siswa.
9. Pastikan validasi tidak mengizinkan siswa menggunakan nomor absen yang sudah dipakai siswa lain dalam kelas yang sama.
10. Untuk siswa lama yang sudah memiliki class_id tetapi belum mempunyai nomor absen, sediakan mekanisme pengisian atau pembaruan data.

Penting: Jangan sampai proses login Google yang sudah berjalan menjadi rusak.

---

BAGIAN 3 — PERLUAS FORMAT UPLOAD TUGAS MAKSIMAL 5 MB

Kondisi sekarang

Upload tugas masih dibatasi maksimal 10 MB dan format file masih terbatas.

Perubahan yang diminta

Ubah batas maksimal ukuran setiap file menjadi 5 MB.

Dukung berbagai format file yang relevan untuk tugas sekolah dan pemrograman.

Contoh format:

Dokumen:

- PDF
- DOC
- DOCX
- TXT
- RTF
- ODT

Spreadsheet:

- XLS
- XLSX
- CSV
- ODS

Presentasi:

- PPT
- PPTX
- ODP

Gambar:

- JPG
- JPEG
- PNG
- GIF
- WEBP
- SVG

Arsip:

- ZIP
- RAR
- 7Z
- TAR
- GZ

Pemrograman:

- HTML
- CSS
- JS
- TS
- PHP
- PY
- JAVA
- C
- CPP
- H
- CS
- SQL
- JSON
- XML
- MD
- GO
- RB
- SWIFT
- KOTLIN
- DART

Ketentuan:

1. Maksimal 5 MB untuk setiap file.
2. Validasi dilakukan di frontend dan backend.
3. Jangan hanya mengandalkan validasi JavaScript.
4. Buat pesan kesalahan yang jelas jika ukuran atau format tidak sesuai.
5. File tetap disimpan menggunakan private storage Laravel.
6. Jangan pernah mengeksekusi file yang diunggah siswa.
7. Tetap gunakan nama penyimpanan acak dan jangan mempercayai nama asli file.
8. Pastikan file dapat diunduh oleh pengguna yang memang memiliki hak akses.
9. Perhatikan MIME type dan ekstensi agar file valid tidak ditolak secara keliru.
10. Tolak executable dan format berbahaya yang tidak diperlukan, seperti EXE, BAT, SH, dan file server executable.

Perbarui juga keterangan format file dan batas ukuran pada halaman upload siswa.

---

BAGIAN 4 — PERBAIKI SISTEM PENILAIAN TUGAS YANG SUDAH SELESAI

Masalah sekarang

Ketika siswa sudah menyelesaikan tugas, terkadang tugas tersebut tidak muncul pada halaman penilaian Guru.

Guru mendapatkan kondisi seperti:

- Tugas tidak ditemukan.
- Tugas yang sudah selesai tidak masuk daftar penilaian.
- Tugas yang sudah dikumpulkan tidak terbaca oleh sistem penilaian.

Tugas kamu

Lakukan pemeriksaan menyeluruh terhadap alur pengerjaan tugas.

Periksa:

- TaskController.
- TaskGroupController.
- AssessmentController.
- ProgressService.
- SubtaskCompletion.
- TaskGroup.
- TaskFile.
- Query pada halaman penilaian.
- Relasi database yang berhubungan dengan tugas.

Perbaiki logika agar:

1. Tugas yang sudah diselesaikan siswa dapat terdeteksi oleh Guru.
2. Tugas yang sudah dikumpulkan masuk ke daftar menunggu penilaian.
3. Bukti file yang diunggah dapat ditemukan dan ditampilkan.
4. Tugas individu dan tugas kelompok sama-sama dapat dinilai.
5. Guru dapat membuka detail tugas tanpa error 404.
6. Progress 100% dan status penyelesaian tersinkronisasi dengan benar.
7. Penilaian hanya dapat dilakukan oleh Guru yang berwenang atas tugas tersebut.
8. Setelah Guru memberikan nilai, status berubah menjadi selesai sesuai alur yang benar.
9. Tugas yang sudah dinilai tetap dapat dilihat dalam riwayat penilaian.
10. Jangan menghilangkan sistem konfirmasi bukti subtask yang sudah ada.

Buat sistem penilaian yang konsisten.

Alur yang diharapkan:

Siswa mengerjakan tugas
→ Menyelesaikan checklist
→ Mengunggah bukti jika diperlukan
→ Mengumpulkan tugas
→ Status menjadi Menunggu Review
→ Tugas muncul pada halaman Penilaian Guru
→ Guru memeriksa file dan hasil pekerjaan
→ Guru memberikan nilai dan feedback
→ Status menjadi Selesai.

Jangan mengubah status menjadi selesai hanya karena siswa mengunggah file. Penilaian akhir tetap menjadi kewenangan Guru.

Tambahkan penanganan untuk data tugas lama yang sudah selesai tetapi belum muncul di daftar penilaian.

---

BAGIAN 5 — PERBAIKI EXPORT EXCEL DAN PDF

Kondisi sekarang

Fitur laporan sudah memiliki export CSV dan halaman cetak PDF, tetapi data anggota kelompok belum ditampilkan secara lengkap.

Perubahan yang diminta

Sempurnakan fitur export agar menghasilkan laporan yang benar-benar menampilkan identitas kelompok dan seluruh anggotanya.

Contoh struktur data:

KELOMPOK 1

| No | Nama Siswa | Nomor Absen | Kelas |
| 1 | Ahmad Fauzi | 03 | X RPL 1 |
| 2 | Putra Maulana Yusuf | 09 | X RPL 1 |
| 3 | Siti Nurhaliza | 12 | X RPL 1 |

KELOMPOK 2

| No | Nama Siswa | Nomor Absen | Kelas |
| 1 | Rizky Pratama | 15 | X RPL 1 |
| 2 | Anggota lainnya | 16 | X RPL 1 |

Ketentuan export Excel

1. Gunakan format XLSX yang valid dan dapat dibuka di Microsoft Excel.
2. Tampilkan nama kelompok.
3. Tampilkan seluruh anggota kelompok.
4. Tampilkan nomor absen di samping nama siswa.
5. Tampilkan kelas.
6. Tampilkan nama tugas dan mata pelajaran.
7. Tampilkan progress.
8. Tampilkan status pengerjaan.
9. Tampilkan nilai dan feedback jika tersedia.
10. Format tabel harus rapi dan mudah dibaca.

Ketentuan export PDF

1. PDF harus memiliki layout profesional.
2. Tampilkan identitas aplikasi dan judul laporan.
3. Tampilkan nama kelas, kelompok, dan seluruh anggota.
4. Nomor absen wajib ditampilkan di samping nama siswa.
5. Tampilkan informasi tugas, progress, status, dan nilai.
6. Gunakan halaman landscape jika tabel membutuhkan ruang lebih luas.
7. Pastikan tabel tidak terpotong ketika dicetak.
8. Tambahkan nomor halaman dan tanggal export.

Export harus mengikuti filter yang dipilih Guru.

Jika filter memilih satu kelompok, hanya kelompok tersebut yang diekspor.

Jika filter memilih seluruh kelas, tampilkan seluruh kelompok beserta anggotanya.

Gunakan library XLSX dan PDF yang kompatibel dengan Laravel 12 jika diperlukan. Periksa dependency yang sudah ada sebelum memasang paket baru.

Jangan hanya mengganti ekstensi CSV menjadi XLSX atau membuat file yang bukan PDF asli.

---

BAGIAN 6 — BUAT HALAMAN LEADERBOARD KHUSUS SISWA

Kondisi sekarang

Dashboard siswa sudah memiliki leaderboard kelompok berdasarkan kelas, tetapi belum tersedia halaman leaderboard khusus dengan filter.

Perubahan yang diminta

Tambahkan menu LEADERBOARD pada sidebar siswa.

Ketika dibuka, tampilkan halaman leaderboard dengan dua pilihan:

1. INDIVIDUAL
2. KELOMPOK

Buat tab atau filter yang mudah digunakan.

A. Leaderboard Individual

Tampilkan:

- Peringkat.
- Foto profil.
- Nama siswa.
- Nomor absen.
- Nama kelompok.
- Total poin.
- Badge peringkat.

Urutkan berdasarkan total poin tertinggi.

B. Leaderboard Kelompok

Tampilkan:

- Peringkat.
- Nama kelompok.
- Jumlah anggota.
- Total poin.
- Daftar anggota.
- Nomor absen setiap anggota.

Urutkan berdasarkan total poin tertinggi.

Ketentuan paling penting

LEADERBOARD HARUS TERPISAH BERDASARKAN KELAS.

Contoh:

X RPL 1 hanya melihat leaderboard X RPL 1.

X RPL 2 hanya melihat leaderboard X RPL 2.

XI AK 1 hanya melihat leaderboard XI AK 1.

Jangan pernah menampilkan ranking gabungan seluruh kelas.

Gunakan class_id milik siswa yang sedang login sebagai batas data utama.

Keamanan:

1. Siswa tidak boleh memanipulasi URL untuk melihat leaderboard kelas lain.
2. Query backend wajib menggunakan class_id siswa yang sedang login.
3. Jangan hanya melakukan filter di frontend.
4. Pastikan data siswa tanpa kelas ditangani dengan benar.

Sistem perhitungan poin

Periksa terlebih dahulu perhitungan poin yang sudah digunakan pada Siswa DashboardController.

Pertahankan konsistensi sistem poin yang sudah berjalan.

Untuk ranking individual dan kelompok, tentukan aturan perhitungan yang jelas dan konsisten:

- Nilai tugas individu menjadi poin siswa.
- Nilai tugas kelompok menjadi poin kelompok.
- Jika nilai tugas kelompok juga digunakan untuk ranking individu, tentukan pembagian poin yang adil dan konsisten untuk seluruh anggota.
- Jangan menghitung satu nilai berkali-kali.
- Hanya tugas yang sudah dinilai dan memiliki score yang valid yang masuk perhitungan.

Buat tampilan leaderboard modern dengan:

- Podium Top 3.
- Medali peringkat.
- Highlight posisi siswa yang sedang login.
- Tabel ranking.
- Badge dan visual poin.
- Tampilan responsif untuk HP.

---

BAGIAN 7 — REDESIGN SELURUH UI/UX MENJADI MODERN TECH

Ini merupakan salah satu bagian yang sangat penting.

Saya ingin seluruh tampilan aplikasi diperbarui agar terlihat seperti platform SaaS pendidikan modern dan profesional.

Jangan hanya mengganti warna.

Lakukan evaluasi desain pada seluruh halaman:

- Login.
- Profil.
- Dashboard Admin.
- Dashboard Guru.
- Dashboard Siswa.
- Manajemen kelas.
- Manajemen kelompok.
- Manajemen siswa.
- Manajemen guru.
- Tugas.
- Detail tugas.
- Penilaian.
- Monitoring.
- Laporan.
- Leaderboard.
- Form upload.
- Notifikasi.

Arah desain

Gunakan konsep:
Modern Education SaaS + Modern Tech Dashboard.

Referensi karakter desain:

- Clean.
- Minimalist.
- Premium.
- Modern.
- Futuristic tetapi tidak berlebihan.
- Professional.
- Responsive.
- User friendly.

Rekomendasi visual:

- Background navy atau slate yang elegan, dengan area konten yang bersih.
- Aksen warna indigo, violet, atau electric blue.
- Card dengan rounded corners.
- Shadow yang halus.
- Typography modern dan konsisten.
- Icon yang seragam.
- Sidebar modern.
- Topbar dengan profil pengguna dan notifikasi.
- Statistik menggunakan card yang rapi.
- Tabel dengan spacing yang nyaman.
- Grafik dashboard yang informatif.
- Animasi ringan dan tidak mengganggu.

Responsivitas

Pastikan seluruh tampilan bekerja baik pada:

- Smartphone Android.
- Tablet.
- Laptop.
- Desktop.

Untuk smartphone:

- Sidebar menjadi drawer atau menu hamburger.
- Tabel dapat di-scroll atau menggunakan tampilan card.
- Form mudah digunakan.
- Tombol tidak terlalu kecil.
- Tidak ada horizontal overflow yang tidak diperlukan.

Konsistensi desain

Admin, Guru, dan Siswa harus mempunyai identitas visual yang sama, tetapi navigasi dan informasi menyesuaikan role masing-masing.

Jangan membuat setiap halaman memiliki desain berbeda-beda.

Buat design system yang konsisten untuk:

- Button.
- Input.
- Select.
- Modal.
- Dropdown.
- Table.
- Card.
- Badge.
- Alert.
- Pagination.
- Sidebar.
- Navbar.

Gunakan komponen Blade yang dapat digunakan kembali jika memungkinkan.

Jangan menghilangkan fungsi Alpine.js dan interaksi yang sudah ada ketika memperbarui frontend.

---

BAGIAN 8 — KETENTUAN PENGERJAAN DAN KEAMANAN

Sebelum mengubah kode:

1. Periksa seluruh struktur project.
2. Baca routes/web.php dan routes/auth.php.
3. Periksa seluruh Controller terkait.
4. Periksa Model dan relasinya.
5. Periksa seluruh migration.
6. Periksa Blade View.
7. Periksa Policies dan Middleware.
8. Periksa test yang sudah ada.
9. Periksa composer.json dan package.json.
10. Identifikasi ketergantungan antarf fitur.

Setelah pemeriksaan, buat rencana pengerjaan berdasarkan tujuh bagian di atas.

Aturan wajib

- Jangan menghapus fitur lama.
- Jangan merusak autentikasi Google.
- Jangan mengubah role Admin, Guru, dan Siswa.
- Jangan menghapus data database.
- Jangan melakukan migrate:fresh.
- Jangan melakukan rollback migration produksi.
- Buat migration baru untuk perubahan struktur database.
- Pertahankan relasi database yang sudah benar.
- Terapkan authorization dan validasi backend.
- Cegah IDOR dan akses lintas kelas.
- Gunakan transaksi database untuk operasi yang membutuhkan konsistensi.
- Jangan menyimpan file tugas di public storage.
- Jangan mengekspos kredensial atau isi file .env.
- Jangan mengklaim fitur selesai sebelum diuji.

Jika menemukan bug lain yang berhubungan langsung dengan tujuh permintaan ini, perbaiki juga.

---

BAGIAN 9 — TAHAPAN IMPLEMENTASI

Kerjakan secara bertahap:

PHASE 1:
Perbaikan database, nomor absen, dan Google login.

PHASE 2:
Manajemen kelompok Guru dan pembatasan akses kelas.

PHASE 3:
Perbaikan upload file dengan batas 5 MB.

PHASE 4:
Perbaikan alur penyelesaian tugas dan sistem penilaian.

PHASE 5:
Penyempurnaan export XLSX dan PDF.

PHASE 6:
Leaderboard individual dan kelompok berdasarkan kelas.

PHASE 7:
Redesign UI/UX seluruh aplikasi.

PHASE 8:
Testing, debugging, dan final verification.

Jangan mengerjakan semuanya secara terburu-buru.

Setiap phase harus diselesaikan dan diverifikasi sebelum melanjutkan ke phase berikutnya.

---

BAGIAN 10 — TESTING DAN VERIFIKASI

Setelah implementasi, lakukan pengujian.

Minimal lakukan:

1. PHP syntax check.
2. Laravel route:list.
3. Migration test.
4. PHPUnit/Pest test yang tersedia.
5. Test login Google dan pengisian kelas.
6. Test nomor absen duplikat.
7. Test guru membuat kelompok.
8. Test guru tidak dapat mengakses kelompok di luar kewenangan.
9. Test upload file 5 MB dan file di atas 5 MB.
10. Test berbagai ekstensi file.
11. Test tugas selesai muncul di daftar penilaian.
12. Test penilaian tugas individu dan kelompok.
13. Test export XLSX.
14. Test export PDF.
15. Test isi laporan dan nomor absen.
16. Test leaderboard individual.
17. Test leaderboard kelompok.
18. Test pembatasan leaderboard per kelas.
19. Test seluruh halaman pada ukuran mobile dan desktop.

Jika ditemukan error, perbaiki terlebih dahulu.

Jangan hanya menjalankan test lalu mengabaikan hasil yang gagal.

---

BAGIAN 11 — LAPORAN HASIL PEKERJAAN

Setelah setiap phase selesai, berikan laporan:

- Fitur yang berhasil diimplementasikan.
- File yang ditambahkan.
- File yang diubah.
- Migration baru.
- Dependency baru jika ada.
- Hasil testing.
- Error yang ditemukan dan diperbaiki.
- Fitur yang masih belum selesai.

Gunakan status:

- COMPLETED: sudah diimplementasikan dan diuji.
- PARTIAL: sudah dibuat tetapi belum seluruhnya berfungsi.
- FAILED: belum berhasil.

Jangan menyatakan sebuah phase COMPLETED jika belum ada verifikasi.

INSTRUKSI TERAKHIR

Mulai dengan membaca project secara menyeluruh dan memahami implementasi yang sudah ada.

Jangan langsung melakukan redesign sebelum memahami struktur dan hubungan backend.

Prioritaskan kestabilan aplikasi, integritas database, keamanan akses, dan fungsi fitur.

Gunakan referensi gambar UI yang saya lampirkan sebagai inspirasi visual untuk arah desain modern tech. Gambar tersebut merupakan referensi, bukan template yang harus disalin persis.

Mulai dari PHASE 1. Jangan lanjut ke phase berikutnya sebelum phase sebelumnya selesai, diuji, dan dilaporkan.
