<x-layouts.app title="Daftar Tugas" role="siswa">
    <div class="space-y-6">

        <div>
            <h3 class="text-xl font-bold text-slate-900">Tugas Saya</h3>
            @if ($group)
                <p class="mt-1 text-sm text-slate-500">
                    Kelompok: <span class="font-medium text-slate-700">{{ $group->name }}</span>
                    <span class="text-slate-300">&middot;</span>
                    {{ $group->schoolClass->name }}
                </p>
            @else
                <p class="mt-1 text-sm text-slate-500">Anda belum tergabung di kelompok manapun. Hubungi admin atau guru Anda.</p>
            @endif
        </div>

        @if ($taskGroups->isEmpty())
            <div class="rounded-2xl border border-slate-100 bg-white px-6 py-10 text-center text-sm text-slate-400 shadow-sm">
                @if ($group)
                    Belum ada tugas yang diberikan kepada Anda atau kelompok Anda.
                @else
                    Bergabunglah dengan sebuah kelompok untuk mulai melihat tugas di sini.
                @endif
            </div>
        @else
            <div class="space-y-3">
                @foreach ($taskGroups as $row)
                    <a
                        href="{{ route('siswa.tasks.show', $row->task) }}"
                        class="block rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:border-slate-200 hover:shadow-md"
                    >
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-semibold text-slate-800">{{ $row->task->title }}</p>
                                <p class="mt-0.5 text-xs text-slate-400">{{ $row->task->subject }} &middot; {{ $row->task->teacher?->name }} &middot; {{ $row->student_id ? 'Tugas individu' : 'Tugas kelompok' }}</p>
                            </div>

                            <div class="flex shrink-0 items-center gap-2">
                                <x-badge :color="\App\Models\TaskGroup::statusColor($row->status)">
                                    {{ $row->progress }}%
                                </x-badge>
                                <x-deadline-badge :deadline="$row->task->deadline" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <x-progress-bar :value="$row->progress" :show-label="false" />
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.app>
