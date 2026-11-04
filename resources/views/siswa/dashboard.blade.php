@php
    $statCards = [
        ['label' => 'Total Tugas', 'value' => $stats['total_tugas'], 'color' => 'text-slate-900'],
        ['label' => 'Sedang Dikerjakan', 'value' => $stats['sedang_dikerjakan'], 'color' => 'text-status-primary', 'dot' => 'bg-status-primary'],
        ['label' => 'Selesai', 'value' => $stats['selesai'], 'color' => 'text-status-success', 'dot' => 'bg-status-success'],
        ['label' => 'Belum Selesai', 'value' => $stats['belum_selesai'], 'color' => 'text-status-danger', 'dot' => 'bg-status-danger'],
    ];
@endphp

<x-layouts.app title="Dashboard Siswa" role="siswa">
    <div class="space-y-6">

        <div class="panel-card flex flex-wrap items-center justify-between gap-3 p-4 sm:p-6">
            <div class="min-w-0">
                <h3 class="text-xl font-bold tracking-tight text-slate-900">Halo, {{ explode(' ', $user->name)[0] }} 👋</h3>

                @if ($group)
                    <p class="mt-1 text-sm text-slate-500">
                        Kelompok: <span class="font-medium text-slate-700">{{ $group->name }}</span>
                        <span class="text-slate-300">&middot;</span>
                        {{ $group->schoolClass->name }}
                    </p>
                @else
                    <p class="mt-1 text-sm text-slate-500">
                        Anda belum tergabung di kelompok manapun. Hubungi admin atau guru Anda.
                    </p>
                @endif
            </div>
            <span class="hidden shrink-0 items-center rounded-full bg-cyan-50 px-3 py-1.5 text-xs font-semibold text-cyan-700 sm:inline-flex">Sistem Stabil</span>
        </div>

        {{-- Kartu statistik --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            @foreach ($statCards as $card)
                <div class="stat-card">
                    <p class="label">{{ $card['label'] }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <p class="value {{ $card['color'] }}">{{ $card['value'] }}</p>
                        @isset($card['dot'])
                            <span class="h-2.5 w-2.5 rounded-full {{ $card['dot'] }}"></span>
                        @endisset
                    </div>
                </div>
            @endforeach
        </div>

        <section class="panel-card p-4 sm:p-6">
            <div class="mb-4 flex flex-wrap items-end justify-between gap-2">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Leaderboard Kelas</h3>
                    <p class="text-sm text-slate-500">{{ $group?->schoolClass?->name ?? $user->schoolClass?->name ?? 'Kelas Anda' }} · Poin berasal dari nilai tugas yang sudah selesai.</p>
                </div>
                <span class="text-xs font-semibold text-slate-500">Nilai tugas menjadi poin</span>
            </div>

            @if ($leaderboard->isEmpty())
                <p class="py-6 text-center text-sm text-slate-400">Belum ada kelompok di kelas ini.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-100 text-xs uppercase text-slate-400">
                            <tr>
                                <th class="py-3 pr-4">Peringkat</th>
                                <th class="py-3 pr-4">Kelompok</th>
                                <th class="py-3 text-right">Poin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($leaderboard as $row)
                                <tr @class(['bg-emerald-50/60' => $group && $row['group']->id === $group->id])>
                                    <td class="py-3 pr-4 font-semibold text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="py-3 pr-4 font-semibold text-slate-800">{{ $row['group']->name }}</td>
                                    <td class="py-3 text-right font-bold text-emerald-700">{{ $row['points'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Daftar tugas --}}
        <div class="panel-card p-4 sm:p-6">
            <h3 class="text-base font-bold text-slate-900">Tugas Terdekat</h3>
            <p class="mb-4 text-sm text-slate-500">Harap perhatikan deadline masing-masing tugas berikut.</p>

            @if ($taskGroups->isEmpty())
                <p class="py-8 text-center text-sm text-slate-400">
                    Belum ada tugas yang diberikan kepada Anda atau kelompok Anda.
                </p>
            @else
                <div class="space-y-3">
                    @foreach ($taskGroups as $row)
                        <div class="rounded-xl border border-slate-100 bg-slate-50/60 p-4">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-semibold text-slate-800">{{ $row->task->title }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ $row->task->subject }} &middot; {{ $row->task->teacher?->name }} &middot; {{ $row->student_id ? 'Individu' : 'Kelompok' }}</p>
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
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
