<x-layouts.app title="Tugas Saya" role="guru">
    <div class="space-y-4">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Tugas Saya</h3>
                <p class="mt-1 text-sm text-slate-500">Kelola tugas untuk kelompok maupun siswa perorangan.</p>
            </div>
            <a href="{{ route('guru.tasks.create') }}" class="shrink-0">
                <x-primary-button type="button">+ Buat Tugas</x-primary-button>
            </a>
        </div>

        <form method="GET" action="{{ route('guru.tasks.index') }}" class="flex flex-wrap gap-2">
            <div class="w-full max-w-xs">
                <x-input type="text" name="search" value="{{ $search }}" placeholder="Cari judul atau mata pelajaran..." />
            </div>

            <div class="w-full sm:w-40">
                <x-select name="status">
                    <option value="">Semua Status</option>
                    @foreach (['draft', 'published', 'in_progress', 'review', 'completed', 'overdue'] as $value)
                        <option value="{{ $value }}" @selected($status === $value)>{{ \App\Models\Task::statusLabel($value) }}</option>
                    @endforeach
                </x-select>
            </div>

            <x-primary-button type="submit" class="shrink-0">Filter</x-primary-button>

            @if ($search !== '' || $status !== '')
                <a href="{{ route('guru.tasks.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Reset
                </a>
            @endif
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Judul Tugas</th>
                            <th class="px-5 py-3">Mata Pelajaran</th>
                            <th class="px-5 py-3">Deadline</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Penerima</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tasks as $task)
                            <tr>
                                <td class="px-5 py-4 text-slate-400">{{ $loop->iteration + ($tasks->currentPage() - 1) * $tasks->perPage() }}</td>
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-800">{{ $task->title }}</p>
                                    <div class="mt-1 max-w-[160px]">
                                        <x-progress-bar :value="round($task->task_groups_avg_progress ?? 0)" />
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-500">{{ $task->subject }}</td>
                                <td class="px-5 py-4">
                                    <x-deadline-badge :deadline="$task->deadline" />
                                </td>
                                <td class="px-5 py-4">
                                    <x-badge :color="\App\Models\Task::statusColor($task->status)">
                                        {{ \App\Models\Task::statusLabel($task->status) }}
                                    </x-badge>
                                </td>
                                <td class="px-5 py-4 text-slate-500">
                                    @if ($task->individual_assignments_count)
                                        {{ $task->individual_assignments_count }} siswa
                                    @else
                                        {{ $task->groups_count }} kelompok
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex gap-3">
                                        <a href="{{ route('guru.tasks.show', $task) }}" class="font-medium text-status-primary hover:underline">Detail</a>
                                        <a href="{{ route('guru.tasks.edit', $task) }}" class="font-medium text-amber-600 hover:underline">Edit</a>

                                        <form method="POST" action="{{ route('guru.tasks.destroy', $task) }}"
                                            onsubmit="return confirm('Hapus tugas {{ $task->title }}? Seluruh progres, komentar, dan file terkait akan ikut terhapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-status-danger hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada tugas. Klik "+ Buat Tugas" untuk membuat tugas pertama.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $tasks->links() }}
    </div>
</x-layouts.app>
