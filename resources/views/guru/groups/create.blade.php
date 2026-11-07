<x-layouts.app title="Tambah Kelompok" role="guru">
    <div class="space-y-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Tambah Kelompok</h3>
            <p class="mt-1 text-sm text-slate-500">Buat kelompok baru pada kelas yang menjadi tanggung jawab Anda.</p>
        </div>

        <div class="max-w-2xl rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <form method="POST" action="{{ route('guru.groups.store') }}" class="space-y-5">
                @csrf

                <div>
                    <x-label for="class_id" value="Kelas" />
                    <select id="class_id" name="class_id" required class="block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-status-primary focus:ring-status-primary">
                        <option value="">Pilih kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" @selected((string) old('class_id') === (string) $class->id)>
                                {{ $class->name }} · {{ $class->academic_year }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('class_id')" />
                    @if ($classes->isEmpty())
                        <p class="mt-1.5 text-xs text-slate-400">
                            Anda belum memiliki kelas tanggung jawab. Hubungi administrator untuk menetapkan kelas yang Anda ampu.
                        </p>
                    @endif
                </div>

                <div>
                    <x-label for="name" value="Nama Kelompok" />
                    <x-input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Contoh: Kelompok 1" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-label for="description" value="Deskripsi (opsional)" />
                    <x-textarea id="description" name="description" rows="3">{{ old('description') }}</x-textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('guru.groups.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>
                    <x-primary-button>Simpan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
