@php
    $statCards = [
        ['label' => 'Total Guru', 'value' => $stats['total_guru'], 'color' => 'text-slate-900'],
        ['label' => 'Total Siswa', 'value' => $stats['total_siswa'], 'color' => 'text-slate-900'],
        ['label' => 'Total Kelas', 'value' => $stats['total_kelas'], 'color' => 'text-slate-900'],
        ['label' => 'Total Kelompok', 'value' => $stats['total_kelompok'], 'color' => 'text-slate-900'],
        ['label' => 'Total Tugas', 'value' => $stats['total_tugas'], 'color' => 'text-slate-900'],
        ['label' => 'Tugas Selesai', 'value' => $stats['tugas_selesai'], 'color' => 'text-status-success', 'dot' => 'bg-status-success'],
        ['label' => 'Tugas Berjalan', 'value' => $stats['tugas_berjalan'], 'color' => 'text-status-primary', 'dot' => 'bg-status-primary'],
        ['label' => 'Tugas Terlambat', 'value' => $stats['tugas_terlambat'], 'color' => 'text-status-danger', 'dot' => 'bg-status-danger'],
    ];
@endphp

<x-layouts.app title="Dashboard Admin" role="admin">
    <div class="space-y-6">

        <div class="hero-card flex flex-wrap items-center justify-between gap-3 p-5 sm:p-7">
            <div class="min-w-0">
                <h3 class="text-xl font-bold tracking-tight text-white">Halo, {{ $user->name }} 👋</h3>
                <p class="mt-1 text-sm text-indigo-100">Berikut ringkasan data sistem TaskGroup.</p>
            </div>
            <span class="hidden shrink-0 items-center rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold text-white ring-1 ring-white/20 sm:inline-flex">Sistem Stabil</span>
        </div>

        {{-- Kartu statistik (skeleton shimmer saat loading) --}}
        <div class="relative" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 700)">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4" :class="loading && 'invisible'">
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

            <div x-show="loading" x-cloak class="absolute inset-0 grid grid-cols-2 gap-4 sm:grid-cols-4" aria-hidden="true">
                @foreach (range(1, 8) as $i)
                    <div class="stat-card">
                        <x-skeleton class="skeleton-text w-24" />
                        <x-skeleton class="mt-3 h-7 w-12" />
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Grafik progres kelompok (spesifikasi 37 Grafik 1) --}}
            <div class="panel-card p-4 sm:p-6 lg:col-span-2">
                <h3 class="mb-4 text-base font-bold text-slate-900">Grafik Progres Kelompok</h3>

                @if ($chartGroupProgress->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada data kelompok.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($chartGroupProgress as $row)
                            <div>
                                <div class="mb-1.5 flex items-center justify-between text-sm">
                                    <span class="font-semibold text-slate-800">{{ $row['label'] }}</span>
                                </div>
                                <x-progress-bar :value="round($row['value'])" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Kelompok tertinggi & terendah --}}
            <div class="space-y-6">
                <div class="panel-card p-4 sm:p-6">
                    <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Progres Tertinggi</h3>
                    @if ($topGroup)
                        <p class="text-base font-bold text-slate-900">{{ $topGroup->name }}</p>
                        <p class="text-xs text-slate-400">{{ $topGroup->schoolClass?->name }}</p>
                        <div class="mt-3">
                            <x-progress-bar :value="round($topGroup->task_groups_avg_progress)" />
                        </div>
                    @else
                        <p class="text-sm text-slate-400">Belum ada data.</p>
                    @endif
                </div>

                <div class="panel-card p-4 sm:p-6">
                    <h3 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Progres Terendah</h3>
                    @if ($bottomGroup)
                        <p class="text-base font-bold text-slate-900">{{ $bottomGroup->name }}</p>
                        <p class="text-xs text-slate-400">{{ $bottomGroup->schoolClass?->name }}</p>
                        <div class="mt-3">
                            <x-progress-bar :value="round($bottomGroup->task_groups_avg_progress)" />
                        </div>
                    @else
                        <p class="text-sm text-slate-400">Belum ada data.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Tugas terbaru --}}
            <div class="panel-card p-4 sm:p-6">
                <h3 class="mb-4 text-base font-bold text-slate-900">Tugas Terbaru</h3>

                @if ($recentTasks->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada tugas.</p>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($recentTasks as $task)
                            <li class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $task->title }}</p>
                                    <p class="text-xs text-slate-400">{{ $task->teacher?->name }} &middot; {{ $task->subject }}</p>
                                </div>
                                <x-deadline-badge :deadline="$task->deadline" class="shrink-0" />
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Aktivitas terbaru --}}
            <div class="panel-card p-4 sm:p-6">
                <h3 class="mb-4 text-base font-bold text-slate-900">Aktivitas Terbaru</h3>

                @if ($recentActivities->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada aktivitas.</p>
                @else
                    <ul class="space-y-4">
                        @foreach ($recentActivities as $activity)
                            <li class="flex gap-3">
                                <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-status-primary"></span>
                                <div>
                                    <p class="text-sm text-slate-700">{{ $activity->description }}</p>
                                    <p class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Grafik 2: jumlah tugas per status (spesifikasi bagian 37) --}}
            <div class="panel-card p-4 sm:p-6">
                <h3 class="mb-4 text-base font-bold text-slate-900">Tugas Berdasarkan Status</h3>

                @if (array_sum($chartTaskStatus) === 0)
                    <p class="text-sm text-slate-400">Belum ada tugas.</p>
                @else
                    <div class="mx-auto max-w-xs">
                        <canvas id="chart-task-status" height="220"></canvas>
                    </div>
                @endif
            </div>

            {{-- Grafik 3: tugas selesai per bulan (spesifikasi bagian 37) --}}
            <div class="panel-card p-4 sm:p-6">
                <h3 class="mb-4 text-base font-bold text-slate-900">Tugas Selesai per Bulan</h3>
                <canvas id="chart-completed-per-month" height="220"></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (!window.Chart) {
                    return;
                }

                const groupProgressCanvas = document.getElementById('chart-group-progress');

                if (groupProgressCanvas) {
                    new window.Chart(groupProgressCanvas, {
                        type: 'bar',
                        data: {
                            labels: @json($chartGroupProgress->pluck('label')),
                            datasets: [{
                                label: 'Rata-rata Progress (%)',
                                data: @json($chartGroupProgress->pluck('value')),
                                backgroundColor: '#2563eb',
                                borderRadius: 6,
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, max: 100 } },
                        },
                    });
                }

                const statusCanvas = document.getElementById('chart-task-status');

                if (statusCanvas) {
                    new window.Chart(statusCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: @json(array_keys($chartTaskStatus)),
                            datasets: [{
                                data: @json(array_values($chartTaskStatus)),
                                backgroundColor: ['#9ca3af', '#3b82f6', '#eab308', '#06b6d4', '#22c55e', '#ef4444'],
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom' } },
                        },
                    });
                }

                new window.Chart(document.getElementById('chart-completed-per-month'), {
                    type: 'line',
                    data: {
                        labels: @json(array_keys($chartCompletedPerMonth)),
                        datasets: [{
                            label: 'Tugas Selesai',
                            data: @json(array_values($chartCompletedPerMonth)),
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.15)',
                            fill: true,
                            tension: 0.3,
                        }],
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                    },
                });
            });
        </script>
    @endpush
</x-layouts.app>
