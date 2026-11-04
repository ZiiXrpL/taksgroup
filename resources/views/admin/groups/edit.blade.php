<x-layouts.app title="Kelola Kelompok" role="admin">
    <div class="mx-auto max-w-3xl space-y-6">

        <div>
            <h3 class="text-xl font-bold text-slate-900">{{ $group->name }}</h3>
            <p class="mt-1 text-sm text-slate-500">
                Kelas: <span class="font-medium text-slate-700">{{ $group->schoolClass->name }}</span>
                <span class="text-slate-300">&middot;</span> Kelas tidak dapat diubah setelah kelompok dibuat.
            </p>
        </div>

        {{-- Info dasar kelompok --}}
        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">

            <form method="POST" action="{{ route('admin.groups.update', $group) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-label for="name" value="Nama Kelompok" />
                    <x-input id="name" name="name" type="text" value="{{ old('name', $group->name) }}" required autofocus />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-label for="description" value="Deskripsi (opsional)" />
                    <x-textarea id="description" name="description" rows="3">{{ old('description', $group->description) }}</x-textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.groups.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                        Kembali ke Daftar
                    </a>
                    <x-primary-button>Simpan Perubahan</x-primary-button>
                </div>
            </form>
        </div>

        {{-- Anggota kelompok --}}
        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <h3 class="mb-4 text-base font-bold text-slate-900">Anggota Kelompok</h3>

            <div class="overflow-hidden rounded-xl border border-slate-100">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-4 py-2.5">Nama</th>
                            <th class="px-4 py-2.5">Peran</th>
                            <th class="px-4 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($group->members as $member)
                            <tr>
                                <td class="px-4 py-2.5 font-semibold text-slate-800">{{ $member->name }}</td>
                                <td class="px-4 py-2.5">
                                    @if ($member->pivot->role === \App\Models\GroupMember::ROLE_LEADER)
                                        <x-badge color="blue">Ketua</x-badge>
                                    @else
                                        <x-badge color="gray">Anggota</x-badge>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex justify-end gap-3">
                                        @if ($member->pivot->role !== \App\Models\GroupMember::ROLE_LEADER)
                                            <form method="POST" action="{{ route('admin.groups.set-leader', $group) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                <button type="submit" class="font-medium text-status-primary hover:underline">
                                                    Jadikan Ketua
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.groups.members.destroy', [$group, $member->pivot->id]) }}"
                                            onsubmit="return confirm('Keluarkan {{ $member->name }} dari kelompok ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-status-danger hover:underline">Keluarkan</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-400">Belum ada anggota di kelompok ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
              </div>
            </div>

            <form method="POST" action="{{ route('admin.groups.members.store', $group) }}" class="mt-5 flex flex-col gap-2 sm:flex-row sm:items-start">
                @csrf
                <div class="w-full">
                    <x-select name="user_id" required>
                        <option value="">-- Pilih siswa untuk ditambahkan --</option>
                        @foreach ($availableStudents as $student)
                            <option value="{{ $student->id }}" @selected(old('user_id') == $student->id)>
                                {{ $student->name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('user_id')" />
                    @if ($availableStudents->isEmpty())
                        <p class="mt-1.5 text-xs text-slate-400">
                            Tidak ada siswa yang tersedia. Semua siswa di kelas {{ $group->schoolClass->name }}
                            sudah tergabung di kelompok lain.
                        </p>
                    @endif
                </div>
                <x-primary-button type="submit" class="shrink-0">+ Tambah Anggota</x-primary-button>
            </form>
        </div>
    </div>
</x-layouts.app>
