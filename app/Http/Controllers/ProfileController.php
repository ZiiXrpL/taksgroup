<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    /**
     * Tampilkan form profil user yang sedang login.
     */
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => request()->user(),
            'classes' => SchoolClass::query()->orderByDesc('academic_year')->orderBy('name')->get(),
        ]);
    }

    /**
     * Perbarui nama, email, dan (opsional) password user yang sedang login.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $oldProfilePhotoPath = $user->profile_photo_path;

        $user->fill($request->safe()->only(['name', 'email']));

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store("profile-photos/{$user->id}", 'local');

            if ($path === false) {
                throw new RuntimeException('Foto profil gagal disimpan.');
            }

            $user->profile_photo_path = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->validated('password'));
        }

        if ($user->isSiswa() && $user->class_id === null) {
            $user->class_id = $request->validated('class_id');
        }

        $user->save();

        if (
            $oldProfilePhotoPath
            && $oldProfilePhotoPath !== $user->profile_photo_path
            && Storage::disk('local')->exists($oldProfilePhotoPath)
            && ! Storage::disk('local')->delete($oldProfilePhotoPath)
        ) {
            throw new RuntimeException('Foto profil lama gagal dihapus.');
        }

        return back()->with('status', 'Profil berhasil diperbarui.');
    }

    /**
     * Tampilkan foto profil hanya kepada pemilik akun.
     */
    public function photo(Request $request): StreamedResponse
    {
        $user = $request->user();

        abort_unless($user->profile_photo_path, 404);
        abort_unless(Storage::disk('local')->exists($user->profile_photo_path), 404);

        return Storage::disk('local')->response(
            $user->profile_photo_path,
            headers: ['Cache-Control' => 'private, max-age=3600'],
            disposition: 'inline',
        );
    }
}
