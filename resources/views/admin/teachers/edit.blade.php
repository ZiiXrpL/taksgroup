<x-layouts.app title="Edit Guru" role="admin">
    <div class="space-y-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Edit Guru</h3>
            <p class="mt-1 text-sm text-slate-500">Perbarui data akun guru. Kosongkan password jika tidak ingin menggantinya.</p>
        </div>

        <div class="max-w-2xl rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-label for="name" value="Nama Lengkap" />
                    <x-input id="name" name="name" type="text" value="{{ old('name', $teacher->name) }}" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-label for="email" value="Email Akademik" />
                    <x-input id="email" name="email" type="email" value="{{ old('email', $teacher->email) }}" required />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <p class="mb-4 text-sm font-semibold text-slate-700">Ganti Password (opsional)</p>

                    <div class="space-y-4">
                        <div>
                            <x-label for="password" value="Password Baru" />
                            <x-input id="password" name="password" type="password" placeholder="Masukkan kata sandi baru" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <x-label for="password_confirmation" value="Konfirmasi Password Baru" />
                            <x-input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi kata sandi baru" autocomplete="new-password" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>
                    <x-primary-button>Simpan Perubahan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
