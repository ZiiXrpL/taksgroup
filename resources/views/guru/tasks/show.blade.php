<x-layouts.app title="Detail Tugas" role="guru">
    <div class="space-y-6">

        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $task->title }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $task->subject }}</p>
                </div>
                <div class="flex gap-2">
                    <x-badge :color="\App\Models\Task::statusColor($task->status)">
                        {{ \App\Models\Task::statusLabel($task->status) }}
                    </x-badge>
                </div>
            </div>

            @if ($task->description)
                <p class="mt-4 whitespace-pre-line text-sm text-slate-600">{{ $task->description }}</p>
            @endif

            <div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <span>Deadline: {{ $task->deadline->translatedFormat('d M Y, H:i') }}</span>
                <x-deadline-badge :deadline="$task->deadline" />
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('guru.tasks.edit', $task) }}">
                    <x-primary-button type="button">Edit Tugas</x-primary-button>
                </a>
                <a href="{{ route('guru.tasks.index') }}" class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-gray-100">
                    Kembali
                </a>
            </div>
        </div>

        @if ($task->individualAssignments->isNotEmpty())
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-base font-bold text-slate-900">Progres Individu</h3>
                    <p class="text-xs text-slate-400">Progres dan bukti dicatat terpisah untuk setiap siswa.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                <th class="px-5 py-3">Siswa</th>
                                <th class="px-5 py-3">Kelas</th>
                                <th class="px-5 py-3">Progres</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($task->individualAssignments as $assignment)
                                <tr>
                                    <td class="px-5 py-3 font-semibold text-slate-800">{{ $assignment->student?->name }}</td>
                                    <td class="px-5 py-3 text-slate-400">{{ $assignment->student?->groups->first()?->schoolClass?->name ?? 'Belum ada kelas' }}</td>
                                    <td class="px-5 py-3"><x-progress-bar :value="$assignment->progress" class="min-w-[120px]" /></td>
                                    <td class="px-5 py-3"><x-badge :color="\App\Models\TaskGroup::statusColor($assignment->status)">{{ \App\Models\TaskGroup::statusLabel($assignment->status) }}</x-badge></td>
                                    <td class="px-5 py-3 text-right"><a href="{{ route('guru.tasks.students.show', [$task, $assignment->student]) }}" class="text-xs font-semibold text-status-primary hover:underline">Tinjau</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900">Progres per Kelompok</h3>
                <p class="text-xs text-slate-400">Progress dihitung otomatis dari checklist yang sudah ditandai selesai. Klik "Detail" untuk memberi komentar &amp; penilaian per kelompok.</p>
            </div>

            @if ($task->groups->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-400">Belum ada kelompok yang menerima tugas ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 text-sm">
                        <thead class="bg-slate-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                                <th class="px-5 py-3">Kelompok</th>
                                <th class="px-5 py-3">Kelas</th>
                                <th class="px-5 py-3">Progress</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Nilai</th>
                                <th class="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($task->groups as $group)
                                <tr>
                                    <td class="px-5 py-3 font-semibold text-slate-800">{{ $group->name }}</td>
                                    <td class="px-5 py-3 text-slate-400">{{ $group->schoolClass?->name }}</td>
                                    <td class="px-5 py-3">
                                        <x-progress-bar :value="$group->pivot->progress" class="min-w-[120px]" />
                                    </td>
                                    <td class="px-5 py-3">
                                        <x-badge :color="\App\Models\TaskGroup::statusColor($group->pivot->status)">
                                            {{ \App\Models\TaskGroup::statusLabel($group->pivot->status) }}
                                        </x-badge>
                                    </td>
                                    <td class="px-5 py-3 text-slate-500">{{ $group->pivot->score ?? '-' }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('guru.tasks.groups.show', [$task, $group]) }}" class="text-xs font-semibold text-status-primary hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900">Checklist Tugas</h3>
                <p class="text-xs text-slate-400">Item yang sama berlaku untuk semua penerima, statusnya dicatat terpisah per kelompok atau siswa.</p>
            </div>

            @if ($task->subtasks->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-400">Tugas ini belum memiliki checklist.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($task->subtasks as $subtask)
                        <li class="px-6 py-3 text-sm text-slate-700">
                            {{ $loop->iteration }}. {{ $subtask->title }}
                            @if ($subtask->description)
                                <p class="mt-0.5 text-xs text-slate-400">{{ $subtask->description }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900">File Hasil Tugas</h3>
                <p class="text-xs text-slate-400">File yang diupload siswa dari setiap kelompok.</p>
            </div>

            @if ($task->files->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-400">Belum ada file yang diupload.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($task->files as $file)
                        <li class="flex items-center justify-between gap-3 px-6 py-3 text-sm">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-slate-800">{{ $file->file_name }}</p>
                                <p class="text-xs text-slate-400">
                                    {{ $file->group?->name ?? $file->student?->name ?? 'Penerima tugas' }} &middot; {{ $file->uploader?->name }} &middot; {{ $file->formatted_size }}
                                </p>
                            </div>
                            <a
                                href="{{ route('files.download', $file) }}"
                                class="shrink-0 rounded-xl px-3 py-1.5 text-xs font-semibold text-status-primary hover:bg-indigo-50"
                            >
                                Unduh
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>
