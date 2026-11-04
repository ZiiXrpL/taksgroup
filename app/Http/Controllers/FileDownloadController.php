<?php

namespace App\Http\Controllers;

use App\Models\TaskFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Endpoint unduh file bersama untuk guru, siswa, dan admin. Diletakkan di
 * luar prefix per-role (bukan Guru\... atau Siswa\...) karena "siapa boleh
 * mengunduh file mana" murni ditentukan oleh TaskFilePolicy, bukan oleh
 * role route seperti CRUD lainnya (spesifikasi bagian 25: "File
 * authorization" - file disimpan di disk privat `task_files`, sehingga
 * TIDAK bisa diakses lewat URL publik langsung, hanya lewat rute ini).
 */
class FileDownloadController extends Controller
{
    public function __invoke(TaskFile $file): StreamedResponse
    {
        $this->authorize('view', $file);

        abort_unless(Storage::disk('task_files')->exists($file->file_path), 404);

        return Storage::disk('task_files')->download(
            $file->file_path,
            $file->file_name,
            ['Content-Type' => $file->file_type]
        );
    }
}
