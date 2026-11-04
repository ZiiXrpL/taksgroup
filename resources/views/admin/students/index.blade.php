<x-layouts.app title="Manajemen Siswa" role="admin">
    <div class="space-y-4">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Daftar Siswa</h3>
                <p class="mt-1 text-sm text-slate-500">Kelola data akun siswa dan keanggotaan kelompok.</p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="shrink-0">
                <x-primary-button type="button">+ Tambah Siswa</x-primary-button>
            </a>
        </div>

        <form method="GET" action="{{ route('admin.students.index') }}" class="flex w-full max-w-sm gap-2">
            <x-input type="text" name="search" value="{{ $search }}" placeholder="Cari nama atau email..." />
            <x-primary-button type="submit" class="shrink-0">Cari</x-primary-button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Siswa</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Jumlah Kelompok</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($students as $student)
                            <tr>
                                <td class="px-5 py-4 text-slate-400">{{ $loop->iteration + ($students->currentPage() - 1) * $students->perPage() }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800">{{ $student->name }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $student->email }}</td>
                                <td class="px-5 py-4"><x-badge color="blue">{{ $student->groups_count }} kelompok</x-badge></td>
                                <td class="px-5 py-4">
                                    <div class="flex gap-3">
                                        <a href="{{ route('admin.students.edit', $student) }}" class="font-medium text-status-primary hover:underline">Edit</a>

                                        <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                                            onsubmit="return confirm('Hapus akun siswa {{ $student->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-status-danger hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada data siswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $students->links() }}
    </div>
</x-layouts.app>
