<x-layouts.app title="Edit Siswa" role="admin">
    <div class="space-y-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Edit Siswa</h3>
            <p class="mt-1 text-sm text-slate-500">Perbarui data akun siswa. Kosongkan password jika tidak ingin menggantinya.</p>
        </div>

        <div class="max-w-2xl rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-label for="name" value="Nama Lengkap" />
                    <x-input id="name" name="name" type="text" value="{{ old('name', $student->name) }}" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-label for="email" value="Email" />
                    <x-input id="email" name="email" type="email" value="{{ old('email', $student->email) }}" required />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="grid gap-5 sm:grid-cols-3">
                    <div class="sm:col-span-2">
                        <x-label for="class_id" value="Kelas" />
                        <x-select id="class_id" name="class_id">
                            <option value="">Belum ada kelas</option>
                            @foreach ($classes as $schoolClass)
                                <option value="{{ $schoolClass->id }}" @selected((string) old('class_id', $student?->class_id) === (string) $schoolClass->id)>
                                    {{ $schoolClass->name }} · {{ $schoolClass->major ?? 'Tanpa jurusan' }} · {{ $schoolClass->academic_year }}
                                </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('class_id')" />
                    </div>

                    <div>
                        <x-label for="attendance_number" value="Nomor Absen" />
                        <x-input id="attendance_number" name="attendance_number" type="number" min="1" max="99" inputmode="numeric" value="{{ old('attendance_number', $student?->attendance_number) }}" placeholder="03" />
                        <x-input-error :messages="$errors->get('attendance_number')" />
                    </div>
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
                    <a href="{{ route('admin.students.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>
                    <x-primary-button>Simpan Perubahan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
