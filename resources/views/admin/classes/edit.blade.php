<x-layouts.app title="Edit Kelas" role="admin">
    <div class="space-y-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Edit Kelas</h3>
            <p class="mt-1 text-sm text-slate-500">Perbarui informasi kelas {{ $schoolClass->name }}.</p>
        </div>

        <div class="max-w-2xl rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <form method="POST" action="{{ route('admin.classes.update', $schoolClass) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-label for="name" value="Nama Kelas" />
                    <x-input id="name" name="name" type="text" value="{{ old('name', $schoolClass->name) }}" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <x-label for="grade" value="Tingkat" />
                        <x-input id="grade" name="grade" type="text" value="{{ old('grade', $schoolClass->grade) }}" required />
                        <x-input-error :messages="$errors->get('grade')" />
                    </div>

                    <div>
                        <x-label for="major" value="Jurusan (opsional)" />
                        <x-input id="major" name="major" type="text" value="{{ old('major', $schoolClass->major) }}" />
                        <x-input-error :messages="$errors->get('major')" />
                    </div>
                </div>

                <div>
                    <x-label for="academic_year" value="Tahun Ajaran" />
                    <x-input id="academic_year" name="academic_year" type="text" value="{{ old('academic_year', $schoolClass->academic_year) }}" required />
                    <x-input-error :messages="$errors->get('academic_year')" />
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('admin.classes.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>
                    <x-primary-button>Simpan Perubahan</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
