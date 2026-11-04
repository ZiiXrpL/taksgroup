<x-layouts.app title="Monitoring Progres" role="admin">
    <div class="space-y-4">

        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <h3 class="text-xl font-bold text-slate-900">Monitoring Progres</h3>
            <p class="mt-1 text-sm text-slate-500">Progres seluruh kelompok pada seluruh tugas di sistem.</p>
        </div>

        <form method="GET" action="{{ route('admin.monitoring.index') }}" class="flex flex-wrap gap-2">
            <div class="w-full max-w-xs">
                <x-input type="text" name="search" value="{{ $search }}" placeholder="Cari judul tugas atau kelompok..." />
            </div>

            <div class="w-full sm:w-44">
                <x-select name="status">
                    <option value="">Semua Status</option>
                    @foreach (['not_started', 'in_progress', 'submitted', 'completed', 'late'] as $value)
                        <option value="{{ $value }}" @selected($status === $value)>{{ \App\Models\TaskGroup::statusLabel($value) }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full sm:w-44">
                <x-select name="class_id">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected($classId == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full sm:w-44">
                <x-select name="teacher_id">
                    <option value="">Semua Guru</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected($teacherId == $teacher->id)>{{ $teacher->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <x-primary-button type="submit" class="shrink-0">Filter</x-primary-button>

            @if ($search !== '' || $status !== '' || $classId !== '' || $teacherId !== '')
                <a href="{{ route('admin.monitoring.index') }}" class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-gray-100">
                    Reset
                </a>
            @endif
        </form>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <th class="px-5 py-3">Kelompok</th>
                            <th class="px-5 py-3">Tugas</th>
                            <th class="px-5 py-3">Guru</th>
                            <th class="px-5 py-3">Progress</th>
                            <th class="px-5 py-3">Deadline</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($rows as $row)
                            <tr>
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.groups.edit', $row->group) }}" class="font-semibold text-slate-800 hover:text-status-primary hover:underline">
                                        {{ $row->group->name }}
                                    </a>
                                    <p class="text-xs text-slate-400">{{ $row->group->schoolClass?->name }}</p>
                                </td>
                                <td class="px-5 py-3 text-slate-500">{{ $row->task->title }}</td>
                                <td class="px-5 py-3 text-slate-500">{{ $row->task->teacher?->name }}</td>
                                <td class="px-5 py-3">
                                    <x-progress-bar :value="$row->progress" class="min-w-[120px]" />
                                </td>
                                <td class="px-5 py-3">
                                    <x-deadline-badge :deadline="$row->task->deadline" />
                                </td>
                                <td class="px-5 py-3">
                                    <x-badge :color="\App\Models\TaskGroup::statusColor($row->status)">
                                        {{ \App\Models\TaskGroup::statusLabel($row->status) }}
                                    </x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada data yang cocok dengan filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $rows->links() }}
    </div>
</x-layouts.app>
