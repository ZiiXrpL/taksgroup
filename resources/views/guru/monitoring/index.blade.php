<x-layouts.app title="Monitoring Progres" role="guru">
    <div class="space-y-4">

        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <h3 class="text-xl font-bold text-slate-900">Monitoring Progres</h3>
            <p class="mt-1 text-sm text-slate-500">Seluruh kelompok dan siswa yang menerima tugas dari Anda, diurutkan berdasarkan deadline terdekat.</p>
        </div>

        <form method="GET" action="{{ route('guru.monitoring.index') }}" class="flex flex-wrap gap-2">
            <div class="w-full max-w-xs">
                <x-input type="text" name="search" value="{{ $search }}" placeholder="Cari judul tugas atau kelompok..." />
            </div>

            <div class="w-full sm:w-48">
                <x-select name="status">
                    <option value="">Semua Status</option>
                    @foreach (['not_started', 'in_progress', 'submitted', 'completed', 'late'] as $value)
                        <option value="{{ $value }}" @selected($status === $value)>{{ \App\Models\TaskGroup::statusLabel($value) }}</option>
                    @endforeach
                </x-select>
            </div>

            <x-primary-button type="submit" class="shrink-0">Filter</x-primary-button>

            @if ($search !== '' || $status !== '')
                <a href="{{ route('guru.monitoring.index') }}" class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-gray-100">
                    Reset
                </a>
            @endif
        </form>

        @if ($rows->isEmpty())
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-sm text-slate-400 shadow-sm">
                Tidak ada kelompok atau siswa yang cocok dengan filter ini.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($rows as $row)
                    <a href="{{ $row->student_id ? route('guru.tasks.students.show', [$row->task, $row->student]) : route('guru.tasks.groups.show', [$row->task, $row->group]) }}"
                        class="block rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:border-status-primary/40 hover:shadow-md">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $row->group?->name ?? $row->student?->name ?? 'Penerima tugas' }}</p>
                                <p class="text-xs text-slate-400">
                                    {{ $row->group?->schoolClass?->name ?? $row->student?->schoolClass?->name ?? 'Individu' }} &middot; {{ $row->student_id ? 'Individu' : 'Kelompok' }} &middot; {{ $row->task->title }}
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <x-deadline-badge :deadline="$row->task->deadline" />
                                <x-badge :color="\App\Models\TaskGroup::statusColor($row->status)">
                                    {{ \App\Models\TaskGroup::statusLabel($row->status) }}
                                </x-badge>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-progress-bar :value="$row->progress" />
                            <p class="mt-1 text-xs text-slate-400">{{ $row->monitoringNote() }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            {{ $rows->links() }}
        @endif
    </div>
</x-layouts.app>
