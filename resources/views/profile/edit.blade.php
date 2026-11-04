<x-layouts.app title="Profil Saya">
    <div class="mx-auto max-w-2xl">
        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-8">

            <div class="mb-8 flex flex-col items-center text-center">
                @if ($user->profile_photo_path)
                    <img src="{{ route('profile.photo') }}" alt="Foto profil {{ $user->name }}" class="h-20 w-20 rounded-full object-cover shadow-md shadow-indigo-200">
                @else
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-indigo-600 text-2xl font-bold text-white shadow-md shadow-indigo-200">
                        {{ collect(explode(' ', $user->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('') }}
                    </div>
                @endif
                <h3 class="mt-4 text-xl font-bold text-slate-900">{{ $user->name }}</h3>
                <p class="text-sm capitalize text-slate-400">{{ $user->role }}</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-label for="profile_photo" value="Foto Profil" />
                    <input id="profile_photo" name="profile_photo" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 shadow-sm file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-slate-500">Format JPG, PNG, atau WebP. Maksimal 2 MB.</p>
                    <x-input-error :messages="$errors->get('profile_photo')" />
                </div>

                <div>
                    <x-label for="name" value="Nama Lengkap" />
                    <x-input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-label for="email" value="Email" />
                    <x-input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                @if ($user->isSiswa())
                    <div>
                        <x-label for="class_id" value="Kelas dan Jurusan" />
                        @if ($user->class_id)
                            <x-input id="class_id" type="text" value="{{ $user->schoolClass?->name }} · {{ $user->schoolClass?->major ?? 'Tanpa jurusan' }}" disabled />
                        @else
                            <select id="class_id" name="class_id" required class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-status-primary focus:ring-status-primary">
                                <option value="">Pilih kelas dan jurusan</option>
                                @foreach ($classes as $schoolClass)
                                    <option value="{{ $schoolClass->id }}" @selected((string) old('class_id') === (string) $schoolClass->id)>
                                        {{ $schoolClass->name }} · {{ $schoolClass->major ?? 'Tanpa jurusan' }} · {{ $schoolClass->academic_year }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                        <x-input-error :messages="$errors->get('class_id')" />
                    </div>
                @endif

                <div class="border-t border-slate-100 pt-5">
                    <p class="mb-4 text-sm font-bold text-slate-900">Ubah Kata Sandi</p>

                    <div class="space-y-4">
                        <div>
                            <x-label for="current_password" value="Kata Sandi Lama" />
                            <x-input id="current_password" name="current_password" type="password" placeholder="Masukkan kata sandi lama Anda" autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('current_password')" />
                        </div>

                        <div>
                            <x-label for="password" value="Kata Sandi Baru" />
                            <x-input id="password" name="password" type="password" placeholder="Masukkan kata sandi baru" autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div>
                            <x-label for="password_confirmation" value="Konfirmasi Kata Sandi Baru" />
                            <x-input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi kata sandi baru" autocomplete="new-password" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <x-primary-button>Simpan Perubahan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
