<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;

/**
 * Aturan upload file tugas yang dipakai bersama oleh validasi backend
 * (FormRequest / Controller) dan keterangan di halaman upload siswa.
 *
 * Batas maksimal 5 MB per file dan format yang didukung mengikuti
 * spesifikasi bagian 3: dokumen, spreadsheet, presentasi, gambar, arsip,
 * dan berkas pemrograman. Executable (EXE, BAT, SH, dsb.) TIDAK ada dalam
 * daftar sehingga otomatis ditolak.
 */
class UploadRules
{
    /**
     * 5 MB dalam kilobyte (satuan aturan `max:` Laravel).
     */
    public const MAX_KB = 5120;

    /**
     * 5 MB dalam byte (dipakai validasi frontend).
     */
    public const MAX_BYTES = 5242880;

    /**
     * Ekstensi yang diizinkan, huruf kecil, tanpa titik.
     *
     * @var array<int, string>
     */
    public const EXTENSIONS = [
        // Dokumen
        'pdf', 'doc', 'docx', 'txt', 'rtf', 'odt',
        // Spreadsheet
        'xls', 'xlsx', 'csv', 'ods',
        // Presentasi
        'ppt', 'pptx', 'odp',
        // Gambar
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
        // Arsip
        'zip', 'rar', '7z', 'tar', 'gz',
        // Pemrograman
        'html', 'css', 'js', 'ts', 'php', 'py', 'java', 'c', 'cpp', 'h',
        'cs', 'sql', 'json', 'xml', 'md', 'go', 'rb', 'swift', 'kotlin', 'dart',
    ];

    /**
     * MIME type yang diterima. Daftar sengaja dibuat lengkap dan permisif
     * (mis. text/plain serta application/octet-stream ikut diterima)
     * karena fileinfo sering mendeteksi dokumen Office sebagai
     * application/zip dan berkas teks sebagai text/plain. Ekstensi tetap
     * diperiksa terpisah, sehingga file valid tidak ditolak secara keliru.
     *
     * @var array<int, string>
     */
    public const MIME_TYPES = [
        'application/pdf',
        'application/x-pdf',
        'application/msword',
        'application/x-ole-storage',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.oasis.opendocument.text',
        'text/plain',
        'text/rtf',
        'application/rtf',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.oasis.opendocument.spreadsheet',
        'text/csv',
        'application/csv',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.oasis.opendocument.presentation',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
        'application/zip',
        'application/x-zip-compressed',
        'application/vnd.rar',
        'application/x-rar-compressed',
        'application/x-7z-compressed',
        'application/x-tar',
        'application/gzip',
        'application/x-gzip',
        'text/html',
        'text/css',
        'text/javascript',
        'application/javascript',
        'application/x-javascript',
        'application/typescript',
        'video/mp2t',
        'text/x-php',
        'application/x-php',
        'text/x-python',
        'text/x-java-source',
        'text/x-c',
        'text/x-c++src',
        'application/sql',
        'application/x-sql',
        'application/json',
        'application/xml',
        'text/xml',
        'text/markdown',
        'text/x-ruby',
        'text/x-swift',
        'application/octet-stream',
    ];

    /**
     * Aturan validasi Laravel untuk sebuah field file.
     *
     * @return array<int, mixed>
     */
    public static function rules(string $field = 'file', bool $required = true): array
    {
        return [
            $required ? 'required' : 'nullable',
            'file',
            'max:' . self::MAX_KB,
            'extensions:' . implode(',', self::EXTENSIONS),
            'mimetypes:' . implode(',', self::MIME_TYPES),
        ];
    }

    /**
     * Daftar ekstensi untuk atribut `accept` pada input file.
     */
    public static function acceptAttribute(): string
    {
        return implode(',', array_map(fn (string $ext) => ".{$ext}", self::EXTENSIONS));
    }

    /**
     * Keterangan format singkat untuk ditampilkan di antarmuka.
     */
    public static function label(): string
    {
        return 'PDF, DOC/DOCX, TXT, RTF, ODT, XLS/XLSX, CSV, ODS, PPT/PPTX, ODP, '
            . 'JPG/JPEG, PNG, GIF, WEBP, SVG, ZIP, RAR, 7Z, TAR, GZ, '
            . 'HTML, CSS, JS, TS, PHP, PY, JAVA, C, CPP, H, CS, SQL, JSON, XML, MD, GO, RB, SWIFT, KOTLIN, DART';
    }

    /**
     * Normalisasi daftar ekstensi menjadi huruf kecil tanpa titik.
     *
     * @param  array<int, string>  $extensions
     * @return array<int, string>
     */
    public static function normalizeExtensions(array $extensions): array
    {
        return array_values(array_filter(array_map(
            fn ($ext) => strtolower(ltrim(trim((string) $ext), '.')),
            $extensions
        )));
    }

    /**
     * Validasi client extension sebuah UploadedFile terhadap daftar yang
     * diizinkan. Dipakai oleh aturan validasi `extensions`.
     */
    public static function hasAllowedExtension(UploadedFile $file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return in_array($extension, self::EXTENSIONS, true);
    }

    /**
     * Daftar ekstensi yang diizinkan (untuk validasi frontend).
     *
     * @return array<int, string>
     */
    public static function extensions(): array
    {
        return self::EXTENSIONS;
    }

    /**
     * Daftar MIME type yang diterima (untuk validasi frontend).
     *
     * @return array<int, string>
     */
    public static function mimeTypes(): array
    {
        return self::MIME_TYPES;
    }
}
