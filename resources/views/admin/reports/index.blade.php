<x-layouts.app title="Laporan Progres" role="admin">
    <div class="space-y-4">

        <div class="flex flex-col gap-4 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Laporan Progres</h3>
                <p class="mt-1 text-sm text-slate-500">Rekap progres kelompok lintas seluruh guru di sistem.</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.reports.export', request()->query()) }}">
                    <x-primary-button type="button" class="!bg-status-success shrink-0">Export Excel (XLSX)</x-primary-button>
                </a>
                <a href="{{ route('admin.reports.print', request()->query()) }}">
                    <x-primary-button type="button" class="shrink-0">Export PDF</x-primary-button>
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-2 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="w-full sm:w-48">
                <x-label for="teacher_id" value="Guru" class="mb-1 text-xs" />
                <x-select name="teacher_id" id="teacher_id">
                    <option value="">Semua Guru</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected($filters['teacher_id'] == $teacher->id)>{{ $teacher->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full sm:w-52">
                <x-label for="task_id" value="Tugas" class="mb-1 text-xs" />
                <x-select name="task_id" id="task_id">
                    <option value="">Semua Tugas</option>
                    @foreach ($taskOptions as $option)
                        <option value="{{ $option->id }}" @selected($filters['task_id'] == $option->id)>{{ $option->title }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full sm:w-40">
                <x-label for="class_id" value="Kelas" class="mb-1 text-xs" />
                <x-select name="class_id" id="class_id">
                    <option value="">Semua Kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}" @selected($filters['class_id'] == $class->id)>{{ $class->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full sm:w-40">
                <x-label for="group_id" value="Kelompok" class="mb-1 text-xs" />
                <x-select name="group_id" id="group_id">
                    <option value="">Semua Kelompok</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}" @selected($filters['group_id'] == $group->id)>{{ $group->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="w-full sm:w-36">
                <x-label for="start_date" value="Dari" class="mb-1 text-xs" />
                <x-input type="date" name="start_date" id="start_date" value="{{ $filters['start_date'] }}" />
            </div>

            <div class="w-full sm:w-36">
                <x-label for="end_date" value="Sampai" class="mb-1 text-xs" />
                <x-input type="date" name="end_date" id="end_date" value="{{ $filters['end_date'] }}" />
            </div>

            <x-primary-button type="submit" class="shrink-0">Terapkan</x-primary-button>

            @if (collect($filters)->filter()->isNotEmpty())
                <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-gray-100">
                    Reset
                </a>
            @endif
        </form>

        @if ($tasks->isEmpty())
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-sm text-slate-400 shadow-sm">
                Tidak ada data yang cocok dengan filter ini.
            </div>
        @else
            <div class="space-y-6">
                @foreach ($tasks as $task)
                    <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-6 py-4">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $task->title }}</h4>
                                <p class="text-xs text-slate-400">
                                    {{ $task->teacher?->name }} &middot; {{ $task->subject }} &middot;
                                    Deadline {{ $task->deadline?->translatedFormat('d M Y') ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100 text-sm">
                                <thead class="bg-slate-50">
                                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        <th class="px-5 py-3">Kelompok</th>
                                        <th class="px-5 py-3">Kelas</th>
                                        <th class="px-5 py-3">Progress</th>
                                        <th class="px-5 py-3">Status</th>
                                        <th class="px-5 py-3">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($task->groups as $group)
                                        <tr>
                                            <td class="px-5 py-3 font-semibold text-slate-800">{{ $group->name }}</td>
                                            <td class="px-5 py-3 text-slate-500">{{ $group->schoolClass?->name ?? '-' }}</td>
                                            <td class="px-5 py-3">
                                                <x-progress-bar :value="$group->pivot->progress" class="min-w-[120px]" />
                                            </td>
                                            <td class="px-5 py-3">
                                                <x-badge :color="\App\Models\TaskGroup::statusColor($group->pivot->status)">
                                                    {{ \App\Models\TaskGroup::statusLabel($group->pivot->status) }}
                                                </x-badge>
                                            </td>
                                            <td class="px-5 py-3 text-slate-500">{{ $group->pivot->score ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
