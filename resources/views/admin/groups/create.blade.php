<x-layouts.app title="Tambah Kelompok" role="admin">
    <div class="space-y-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Tambah Kelompok</h3>
            <p class="mt-1 text-sm text-slate-500">Setelah kelompok dibuat, Anda akan diarahkan ke halaman kelola untuk menambahkan anggota.</p>
        </div>

        <div class="max-w-2xl rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <form method="POST" action="{{ route('admin.groups.store') }}" class="space-y-5">
                @csrf

                <div>
                    <x-label for="class_id" value="Kelas" />
                    <x-select id="class_id" name="class_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>
                                {{ $class->name }} ({{ $class->academic_year }})
                            </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('class_id')" />
                </div>

                <div>
                    <x-label for="name" value="Nama Kelompok" />
                    <x-input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Kelompok 1" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-label for="description" value="Deskripsi (opsional)" />
                    <x-textarea id="description" name="description" rows="3">{{ old('description') }}</x-textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.groups.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>
                    <x-primary-button>Simpan & Lanjut Kelola Anggota</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
