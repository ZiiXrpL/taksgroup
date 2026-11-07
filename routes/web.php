<?php

use App\Http\Controllers\Admin\ClassController as AdminClassController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GroupController as AdminGroupController;
use App\Http\Controllers\Admin\GroupMemberController as AdminGroupMemberController;
use App\Http\Controllers\Admin\MonitoringController as AdminMonitoringController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\Guru\AssessmentController as GuruAssessmentController;
use App\Http\Controllers\Guru\CommentController as GuruCommentController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\GroupController as GuruGroupController;
use App\Http\Controllers\Guru\MonitoringController as GuruMonitoringController;
use App\Http\Controllers\Guru\ReportController as GuruReportController;
use App\Http\Controllers\Guru\TaskController as GuruTaskController;
use App\Http\Controllers\Guru\TaskGroupController as GuruTaskGroupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Siswa\CommentController as SiswaCommentController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\FileController as SiswaFileController;
use App\Http\Controllers\Siswa\LeaderboardController as SiswaLeaderboardController;
use App\Http\Controllers\Siswa\TaskController as SiswaTaskController;
use App\Models\SubtaskCompletion;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rute autentikasi (login/logout) & profil ada di routes/auth.php.
| Rute di bawah ini dikelompokkan per role menggunakan middleware 'role'
| (Tahap 6). Setiap grup akan diisi CRUD lengkap pada tahap-tahap
| berikutnya (Tahap 8: Admin - selesai, Tahap 9: Guru - selesai,
| Tahap 11: Siswa - menyusul).
|
*/

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

require __DIR__ . '/auth.php';

// ------------------------------------------------------------------
// ADMIN
// ------------------------------------------------------------------
Route::middleware(['auth', 'role:' . User::ROLE_ADMIN])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Tahap 8: CRUD data master (guru, siswa, kelas). Kelompok & anggota
        // kelompok sengaja ditangani terpisah pada Tahap 10.
        //
        // URI tetap berbahasa Indonesia (mis. /admin/guru) agar rapi bagi
        // pengguna, tetapi nama route sengaja di-override ke Bahasa Inggris
        // (admin.teachers.*, dst) via ->names() agar konsisten dengan pola
        // penamaan route lain (groups, tasks, monitoring, reports) yang
        // dipakai di komponen sidebar (components/layouts/app.blade.php).
        Route::resource('guru', AdminTeacherController::class)
            ->parameters(['guru' => 'teacher'])
            ->names('teachers')
            ->except(['show']);

        Route::resource('siswa', AdminStudentController::class)
            ->parameters(['siswa' => 'student'])
            ->names('students')
            ->except(['show']);

        Route::resource('kelas', AdminClassController::class)
            ->parameters(['kelas' => 'class'])
            ->names('classes')
            ->except(['show']);

        // Tahap 10: kelompok & anggota kelompok. Halaman edit kelompok
        // sekaligus berfungsi sebagai halaman manajemen anggota, sehingga
        // rute anggota (members.*) dan penetapan ketua (set-leader) dibuat
        // terpisah dari resource utama, bukan sebagai resource CRUD sendiri.
        Route::resource('kelompok', AdminGroupController::class)
            ->parameters(['kelompok' => 'group'])
            ->names('groups')
            ->except(['show']);

        Route::patch('kelompok/{group}/ketua', [AdminGroupController::class, 'setLeader'])
            ->name('groups.set-leader');

        Route::post('kelompok/{group}/anggota', [AdminGroupMemberController::class, 'store'])
            ->name('groups.members.store');

        Route::delete('kelompok/{group}/anggota/{member}', [AdminGroupMemberController::class, 'destroy'])
            ->name('groups.members.destroy');

        // Tahap 18: monitoring progres lintas seluruh guru (spesifikasi
        // bagian 3: "Melihat semua progres kelompok").
        Route::get('monitoring', [AdminMonitoringController::class, 'index'])->name('monitoring.index');

        // Tahap 19: laporan progres lintas sistem (spesifikasi bagian 3 &
        // 38), dengan ekspor CSV dan halaman cetak.
        Route::get('laporan', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('laporan/export', [AdminReportController::class, 'export'])->name('reports.export');
        Route::get('laporan/cetak', [AdminReportController::class, 'print'])->name('reports.print');
    });

// ------------------------------------------------------------------
// GURU
// ------------------------------------------------------------------
Route::middleware(['auth', 'role:' . User::ROLE_GURU])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

        // Bagian 1: manajemen kelompok oleh guru, dibatasi pada kelas yang
        // menjadi tanggung jawabnya (relasi teacher_classes). Guru yang
        // mengakses kelompok di luar kewenangannya mendapat 404.
        Route::resource('kelompok', GuruGroupController::class)
            ->parameters(['kelompok' => 'group'])
            ->names('groups')
            ->except(['show']);

        Route::patch('kelompok/{group}/ketua', [GuruGroupController::class, 'setLeader'])
            ->name('groups.set-leader');

        Route::post('kelompok/{group}/anggota', [GuruGroupController::class, 'addMember'])
            ->name('groups.members.store');

        Route::delete('kelompok/{group}/anggota/{member}', [GuruGroupController::class, 'removeMember'])
            ->name('groups.members.destroy');

        // Tahap 9: guru membuat & mengelola tugas miliknya sendiri, lalu
        // memberikannya ke satu atau beberapa kelompok sekaligus (TaskPolicy
        // memastikan guru tidak bisa mengakses tugas guru lain).
        Route::resource('tugas', GuruTaskController::class)
            ->parameters(['tugas' => 'task'])
            ->names('tasks');

        // Tahap 15 & 16: halaman detail pekerjaan sebuah kelompok pada
        // tugas tertentu (checklist, file, komentar, penilaian) - progress,
        // komentar, dan nilai adalah data milik pasangan task+group
        // (spesifikasi bagian 39), sehingga URL-nya selalu membawa
        // keduanya sekaligus.
        Route::get('tugas/{task}/kelompok/{group}', [GuruTaskGroupController::class, 'show'])
            ->name('tasks.groups.show');

        Route::get('tugas/{task}/siswa/{student}', [GuruTaskGroupController::class, 'showIndividual'])
            ->name('tasks.students.show');
        Route::patch('tugas/{task}/siswa/{student}/subtask/{subtaskCompletion}/confirm', [GuruTaskGroupController::class, 'confirmIndividualSubtask'])
            ->name('tasks.students.subtask.confirm');
        Route::patch('tugas/{task}/siswa/{student}/subtask/{subtaskCompletion}/reject', [GuruTaskGroupController::class, 'rejectIndividualSubtask'])
            ->name('tasks.students.subtask.reject');
        Route::put('tugas/{task}/siswa/{student}/penilaian', [GuruTaskGroupController::class, 'updateIndividualAssessment'])
            ->name('tasks.students.assessment.update');

        Route::post('tugas/{task}/kelompok/{group}/komentar', [GuruCommentController::class, 'store'])
            ->name('tasks.groups.comments.store');

        Route::put('tugas/{task}/kelompok/{group}/penilaian', [GuruAssessmentController::class, 'update'])
            ->name('tasks.groups.assessment.update');

        // Konfirmasi & tolak bukti subtask siswa oleh guru
        Route::patch('tugas/{task}/kelompok/{group}/subtask/{subtaskCompletion}/confirm', [GuruTaskGroupController::class, 'confirmSubtask'])
            ->name('tasks.groups.subtask.confirm');

        Route::patch('tugas/{task}/kelompok/{group}/subtask/{subtaskCompletion}/reject', [GuruTaskGroupController::class, 'rejectSubtask'])
            ->name('tasks.groups.subtask.reject');

        // Daftar penilaian: rekap seluruh kelompok yang sudah selesai
        // (menunggu review / sudah dinilai) lintas semua tugas guru ini,
        // supaya tidak perlu membuka tugas satu per satu untuk menemukan
        // kelompok mana yang siap dinilai.
        Route::get('penilaian', [GuruAssessmentController::class, 'index'])->name('assessments.index');

        // Tahap 18: halaman monitoring khusus (spesifikasi bagian 15) -
        // seluruh kelompok penerima tugas guru ini, dengan pencarian &
        // filter (spesifikasi bagian 22), terpisah dari ringkasan 8 baris
        // teratas yang sudah ada di dashboard.
        Route::get('monitoring', [GuruMonitoringController::class, 'index'])->name('monitoring.index');

        // Tahap 19: laporan progres (spesifikasi bagian 38), dengan ekspor
        // CSV dan halaman cetak (lihat catatan di Guru\ReportController).
        Route::get('laporan', [GuruReportController::class, 'index'])->name('reports.index');
        Route::get('laporan/export', [GuruReportController::class, 'export'])->name('reports.export');
        Route::get('laporan/cetak', [GuruReportController::class, 'print'])->name('reports.print');
    });

// ------------------------------------------------------------------
// SISWA
// ------------------------------------------------------------------
Route::middleware(['auth', 'role:' . User::ROLE_SISWA])
    ->middleware('student.class')
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

        // Bagian 6: leaderboard individual & kelompok, dibatasi kelas siswa.
        Route::get('leaderboard', [SiswaLeaderboardController::class, 'index'])->name('leaderboard.index');

        // Tahap 11 & 12: siswa melihat tugas kelompoknya, menandai
        // checklist selesai, dan mengupload file hasil tugas (Tahap 14).
        // Sengaja bukan Route::resource - siswa tidak pernah membuat/
        // menghapus tugas, hanya melihat & berinteraksi dengan checklist.
        Route::get('tugas', [SiswaTaskController::class, 'index'])->name('tasks.index');
        Route::get('tugas/{task}', [SiswaTaskController::class, 'show'])->name('tasks.show');
        // Siswa mengirim bukti pengerjaan subtask (upload file bukti).
        // Progress tidak langsung bertambah - harus dikonfirmasi guru dulu.
        Route::post('tugas/{task}/checklist/{subtask}/proof', [SiswaTaskController::class, 'submitSubtaskProof'])
            ->name('tasks.checklist.submit-proof');
        Route::post('tugas/{task}/kumpulkan', [SiswaTaskController::class, 'submitTask'])
            ->name('tasks.submit');

        Route::post('tugas/{task}/file', [SiswaFileController::class, 'store'])->name('files.store');
        Route::delete('file/{file}', [SiswaFileController::class, 'destroy'])->name('files.destroy');

        // Tahap 15: siswa berkomentar pada tugas kelompoknya sendiri -
        // kelompok selalu diselesaikan dari kelompok aktif siswa yang
        // login (lihat Siswa\CommentController), tidak pernah dari input.
        Route::post('tugas/{task}/komentar', [SiswaCommentController::class, 'store'])->name('tasks.comments.store');
    });

// ------------------------------------------------------------------
// FILE (BERSAMA)
// ------------------------------------------------------------------
// Unduh file hasil tugas: dapat diakses guru, siswa, maupun admin - hak
// aksesnya ditentukan oleh TaskFilePolicy per-file (bukan oleh role route),
// sehingga tidak diletakkan di bawah prefix admin/guru/siswa manapun
// (spesifikasi bagian 25 & 29).
Route::middleware(['auth'])
    ->get('/files/{file}/download', FileDownloadController::class)
    ->name('files.download');

// Unduh file bukti pengerjaan subtask
Route::middleware(['auth'])
    ->get('/proofs/{subtaskCompletion}/download', function (\Illuminate\Http\Request $request, SubtaskCompletion $subtaskCompletion) {
        abort_unless($subtaskCompletion->proof_file_path && Storage::disk('task_files')->exists($subtaskCompletion->proof_file_path), 404);

        $subtaskCompletion->loadMissing(['subtask.task', 'group.members']);
        $user = $request->user();
        $canAccess = $user->isAdmin()
            || ($user->isGuru() && $subtaskCompletion->subtask->task->teacher_id === $user->id)
            || ($user->isSiswa() && (
                ($subtaskCompletion->student_id === $user->id
                    && $subtaskCompletion->subtask->task->taskGroups()->where('student_id', $user->id)->exists())
                || ($subtaskCompletion->group !== null
                    && $subtaskCompletion->subtask->task->taskGroups()->where('group_id', $subtaskCompletion->group_id)->exists()
                    && $subtaskCompletion->group->members()->where('users.id', $user->id)->exists())
            ));
        abort_unless($canAccess, 403);

        return Storage::disk('task_files')->download(
            $subtaskCompletion->proof_file_path,
            $subtaskCompletion->proof_file_name
        );
    })->name('proofs.download');

// ------------------------------------------------------------------
// NOTIFIKASI (BERSAMA)
// ------------------------------------------------------------------
// Sama seperti unduhan file di atas: notifikasi bisa dimiliki user dari
// ketiga role, jadi rutenya tidak diletakkan di bawah prefix admin/guru/
// siswa manapun. Kepemilikan tiap notifikasi dicek di NotificationController
// (spesifikasi bagian 25: "Prevent IDOR").
Route::middleware(['auth'])
    ->prefix('notifikasi')
    ->name('notifications.')
    ->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('{notification}/baca', [NotificationController::class, 'markRead'])->name('read');
        Route::post('baca-semua', [NotificationController::class, 'markAllRead'])->name('read-all');
    });
