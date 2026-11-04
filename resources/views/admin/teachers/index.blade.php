<x-layouts.app title="Manajemen Guru" role="admin">
    <div class="space-y-4">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Daftar Guru Pengajar</h3>
                <p class="mt-1 text-sm text-slate-500">Kelola data akun guru pengampu mata pelajaran akademik.</p>
            </div>
            <a href="{{ route('admin.teachers.create') }}" class="shrink-0">
                <x-primary-button type="button">+ Tambah Guru</x-primary-button>
            </a>
        </div>

        <form method="GET" action="{{ route('admin.teachers.index') }}" class="flex w-full max-w-sm gap-2">
            <x-input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..." />
            <x-primary-button type="submit" class="shrink-0">Cari</x-primary-button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Guru</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Total Tugas</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($teachers as $teacher)
                            <tr>
                                <td class="px-5 py-4 text-slate-400">{{ $loop->iteration + ($teachers->currentPage() - 1) * $teachers->perPage() }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $teacher->name }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $teacher->email }}</td>
                                <td class="px-5 py-4"><x-badge color="blue">{{ $teacher->tasks_as_teacher_count }} tugas</x-badge></td>
                                <td class="px-5 py-4">
                                    <div class="flex gap-3">
                                        <a href="{{ route('admin.teachers.edit', $teacher) }}" class="font-medium text-status-primary hover:underline">Edit</a>

                                        <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}"
                                            onsubmit="return confirm('Hapus akun guru {{ $teacher->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-status-danger hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada data guru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $teachers->links() }}
    </div>
</x-layouts.app>
