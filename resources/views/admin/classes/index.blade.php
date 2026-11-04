<x-layouts.app title="Manajemen Kelas" role="admin">
    <div class="space-y-4">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Daftar Kelas Akademik</h3>
                <p class="mt-1 text-sm text-slate-500">Daftar kelas beserta tingkat, jurusan, dan jumlah kelompok yang terintegrasi.</p>
            </div>
            <a href="{{ route('admin.classes.create') }}" class="shrink-0">
                <x-primary-button type="button">+ Tambah Kelas</x-primary-button>
            </a>
        </div>

        <form method="GET" action="{{ route('admin.classes.index') }}" class="flex w-full max-w-sm gap-2">
            <x-input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, tingkat, jurusan..." />
            <x-primary-button type="submit" class="shrink-0">Cari</x-primary-button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Kelas</th>
                            <th class="px-5 py-3">Tingkat</th>
                            <th class="px-5 py-3">Jurusan</th>
                            <th class="px-5 py-3">Tahun Ajaran</th>
                            <th class="px-5 py-3">Jumlah Kelompok</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($classes as $class)
                            <tr>
                                <td class="px-5 py-4 text-slate-400">{{ $loop->iteration + ($classes->currentPage() - 1) * $classes->perPage() }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $class->name }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $class->grade }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $class->major ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $class->academic_year }}</td>
                                <td class="px-5 py-4"><x-badge color="blue">{{ $class->groups_count }} kelompok</x-badge></td>
                                <td class="px-5 py-4">
                                    <div class="flex gap-3">
                                        <a href="{{ route('admin.classes.edit', $class) }}" class="font-medium text-status-primary hover:underline">Edit</a>

                                        <form method="POST" action="{{ route('admin.classes.destroy', $class) }}"
                                            onsubmit="return confirm('Hapus kelas {{ $class->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-status-danger hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $classes->links() }}
    </div>
</x-layouts.app>
