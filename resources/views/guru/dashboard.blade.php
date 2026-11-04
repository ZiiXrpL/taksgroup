@php
    $statCards = [
        ['label' => 'Total Tugas', 'value' => $stats['total_tugas'], 'color' => 'text-slate-900'],
        ['label' => 'Tugas Aktif', 'value' => $stats['tugas_aktif'], 'color' => 'text-status-primary', 'dot' => 'bg-status-primary'],
        ['label' => 'Tugas Selesai', 'value' => $stats['tugas_selesai'], 'color' => 'text-status-success', 'dot' => 'bg-status-success'],
        ['label' => 'Tugas Terlambat', 'value' => $stats['tugas_terlambat'], 'color' => 'text-status-danger', 'dot' => 'bg-status-danger'],
    ];

    $uniqueTasks = $monitoring->unique('task_id')->take(6);
@endphp

<x-layouts.app title="Dashboard Guru" role="guru">
    <div class="space-y-6">

        <div class="panel-card flex flex-wrap items-center justify-between gap-3 p-4 sm:p-6">
            <div class="min-w-0">
                <h3 class="text-xl font-bold tracking-tight text-slate-900">Halo, {{ $user->name }} 👋</h3>
                <p class="mt-1 text-sm text-slate-500">Ringkasan tugas kelompok dan individu Anda.</p>
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

        @if ($menungguPenilaian > 0)
            <a href="{{ route('guru.assessments.index') }}"
                class="flex items-center justify-between gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm shadow-sm transition hover:border-amber-300">
                <span class="font-medium text-amber-800">
                    {{ $menungguPenilaian }} kelompok sudah mengumpulkan tugas dan menunggu Anda nilai.
                </span>
                <span class="shrink-0 font-semibold text-amber-800">Beri Nilai &rarr;</span>
            </a>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Tugas Anda --}}
            <div class="panel-card p-4 sm:p-6">
                <h3 class="mb-4 text-base font-bold text-slate-900">Tugas Anda</h3>

                @if ($uniqueTasks->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada tugas yang diberikan kepada kelompok atau siswa.</p>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach ($uniqueTasks as $row)
                            <li class="flex items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $row->task->title }}</p>
                                    <p class="text-xs text-slate-400">{{ $row->task->teacher?->name ?? $user->name }} &middot; {{ $row->task->subject }} &middot; {{ $row->student_id ? 'Individu: '.$row->student?->name : 'Kelompok: '.$row->group?->name }}</p>
                                </div>
                                <x-badge :color="\App\Models\TaskGroup::statusColor($row->status)" class="shrink-0">
                                    {{ \App\Models\TaskGroup::statusLabel($row->status) }}
                                </x-badge>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-3 text-right">
                        <a href="{{ route('guru.tasks.index') }}" class="text-sm font-semibold text-status-primary hover:underline">
                            Lihat semua tugas &rarr;
                        </a>
                    </div>
                @endif
            </div>

            {{-- Progres Kelompok --}}
            <div class="panel-card p-4 sm:p-6">
                <h3 class="mb-4 text-base font-bold text-slate-900">Progres Kelompok</h3>

                @if ($groupProgress->isEmpty())
                    <p class="text-sm text-slate-400">Belum ada data progres.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($groupProgress as $row)
                            <div>
                                <div class="mb-1.5 flex items-center justify-between text-sm">
                                    <span class="font-semibold text-slate-800">{{ $row['group']->name }}</span>
                                </div>
                                <x-progress-bar :value="$row['avg']" />
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-right">
                        <a href="{{ route('guru.monitoring.index') }}" class="text-sm font-semibold text-status-primary hover:underline">
                            Lihat monitoring lengkap &rarr;
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Grafik distribusi status kelompok (Chart.js, Tahap 18) --}}
        <div class="panel-card p-4 sm:p-6">
            <h3 class="mb-4 text-base font-bold text-slate-900">Distribusi Status Kelompok</h3>

            @if (array_sum($chartStatusDistribution) === 0)
                <p class="text-sm text-slate-400">Belum ada data status.</p>
            @else
                <div class="mx-auto max-w-xs">
                    <canvas id="chart-status-distribution" height="220"></canvas>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (!window.Chart) {
                    return;
                }

                const statusCanvas = document.getElementById('chart-status-distribution');

                if (statusCanvas) {
                    new window.Chart(statusCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: @json(array_keys($chartStatusDistribution)),
                            datasets: [{
                                data: @json(array_values($chartStatusDistribution)),
                                backgroundColor: ['#9ca3af', '#eab308', '#06b6d4', '#22c55e', '#ef4444'],
                            }],
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom' } },
                        },
                    });
                }
            });
        </script>
    @endpush
</x-layouts.app>
