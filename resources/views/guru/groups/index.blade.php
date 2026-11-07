<x-layouts.app title="Manajemen Kelompok" role="guru">
    <div class="space-y-4">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Kelompok di Kelas Saya</h3>
                <p class="mt-1 text-sm text-slate-500">Kelola pembagian kelompok pada kelas yang menjadi tanggung jawab Anda.</p>
            </div>
            <a href="{{ route('guru.groups.create') }}" class="shrink-0">
                <x-primary-button type="button">+ Tambah Kelompok</x-primary-button>
            </a>
        </div>

        <form method="GET" action="{{ route('guru.groups.index') }}" class="flex w-full max-w-sm gap-2">
            <x-input type="text" name="search" value="{{ $search }}" placeholder="Cari nama kelompok/kelas..." />
            <x-primary-button type="submit" class="shrink-0">Cari</x-primary-button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Kelompok</th>
                            <th class="px-5 py-3">Kelas</th>
                            <th class="px-5 py-3">Ketua</th>
                            <th class="px-5 py-3">Jumlah Anggota</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($groups as $group)
                            <tr>
                                <td class="px-5 py-4 text-slate-400">{{ $loop->iteration + ($groups->currentPage() - 1) * $groups->perPage() }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $group->name }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $group->schoolClass?->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $group->leader?->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $group->members_count }} Siswa</td>
                                <td class="px-5 py-4">
                                    <div class="flex gap-3">
                                        <a href="{{ route('guru.groups.edit', $group) }}" class="font-medium text-status-primary hover:underline">
                                            Kelola
                                        </a>

                                        <form method="POST" action="{{ route('guru.groups.destroy', $group) }}"
                                            onsubmit="return confirm('Hapus kelompok {{ $group->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-status-danger hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada kelompok di kelas yang Anda ampu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $groups->links() }}
    </div>
</x-layouts.app>
