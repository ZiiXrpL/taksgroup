<x-layouts.app title="Leaderboard" role="siswa">
    <div class="space-y-6">

        <div class="hero-card flex flex-wrap items-center justify-between gap-3 p-5 sm:p-7">
            <div class="min-w-0">
                <h3 class="text-xl font-bold tracking-tight text-white">Leaderboard {{ $class?->name ?? '' }}</h3>
                <p class="mt-1 text-sm text-indigo-100">
                    Poin dari nilai tugas yang sudah dinilai. Hanya kelas Anda yang ditampilkan.
                </p>
            </div>
            <span class="hidden shrink-0 items-center rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold text-white ring-1 ring-white/20 sm:inline-flex">
                {{ $class?->academic_year ?? '' }}
            </span>
        </div>

        {{-- Tab Individual / Kelompok --}}
        <div class="flex gap-2">
            <a href="{{ route('siswa.leaderboard.index', ['tab' => 'individual']) }}"
                @class([
                    'rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                    'bg-status-primary text-white shadow-glow' => $tab === 'individual',
                    'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' => $tab !== 'individual',
                ])>
                Individual
            </a>
            <a href="{{ route('siswa.leaderboard.index', ['tab' => 'kelompok']) }}"
                @class([
                    'rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                    'bg-status-primary text-white shadow-glow' => $tab === 'kelompok',
                    'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' => $tab !== 'kelompok',
                ])>
                Kelompok
            </a>
        </div>

        @if (!$class)
            <div class="panel-card p-8 text-center text-sm text-slate-400">
                Anda belum memiliki kelas. Leaderboard hanya tersedia per kelas.
            </div>
        @elseif ($tab === 'individual')
            @php $top3 = $individual->take(3)->values(); @endphp

            @if ($individual->isEmpty())
                <div class="panel-card p-8 text-center text-sm text-slate-400">
                    Belum ada siswa di kelas ini.
                </div>
            @else
                {{-- Podium Top 3 --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    @foreach ($top3 as $row)
                        @php $rank = $loop->iteration; @endphp
                        <div @class([
                            'panel-card relative p-5 text-center',
                            'ring-2 ring-amber-300 shadow-card-hover' => $rank === 1,
                        ])>
                            <div class="text-3xl">{{ ['🥇', '🥈', '🥉'][$rank - 1] ?? '' }}</div>
                            <div class="mx-auto mt-3 h-16 w-16 overflow-hidden rounded-full ring-2 ring-indigo-100">
                                @if ($row['student']->profile_photo_path)
                                    <img src="{{ route('profile.photo') }}" alt="{{ $row['student']->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-600 text-lg font-bold text-white">
                                        {{ collect(explode(' ', $row['student']->name))->map(fn ($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('') }}
                                    </div>
                                @endif
                            </div>
                            <p class="mt-3 font-bold text-slate-900">{{ $row['student']->name }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">
                                No. Absen {{ $row['student']->attendanceLabel() }}
                                @if ($row['student']->groups->first())
                                    · {{ $row['student']->groups->first()->name }}
                                @endif
                            </p>
                            <p class="mt-2 text-lg font-bold text-status-primary">{{ $row['points'] }} <span class="text-xs font-semibold text-slate-400">poin</span></p>
                            @if ($row['student']->id === auth()->id())
                                <span class="mt-2 inline-flex rounded-full bg-indigo-50 px-2.5 py-1 text-[11px] font-semibold text-indigo-700 ring-1 ring-indigo-100">Anda</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Tabel ranking --}}
                <div class="panel-card overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-slate-100 text-xs uppercase text-slate-400">
                                <tr>
                                    <th class="px-5 py-3">Peringkat</th>
                                    <th class="px-5 py-3">Siswa</th>
                                    <th class="px-5 py-3">Kelompok</th>
                                    <th class="px-5 py-3 text-right">Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($individual as $row)
                                    <tr @class(['bg-indigo-50/60' => $row['student']->id === auth()->id()])>
                                        <td class="px-5 py-3 font-bold {{ $loop->iteration <= 3 ? 'text-amber-500' : 'text-slate-400' }}">
                                            {{ $loop->iteration <= 3 ? ['🥇','🥈','🥉'][$loop->iteration - 1] : $loop->iteration }}
                                        </td>
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="h-9 w-9 shrink-0 overflow-hidden rounded-full ring-1 ring-slate-200">
                                                    @if ($row['student']->profile_photo_path)
                                                        <img src="{{ route('profile.photo') }}" alt="" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-500 to-violet-600 text-xs font-bold text-white">
                                                            {{ collect(explode(' ', $row['student']->name))->map(fn ($w) => strtoupper(substr($w, 0, 1)))->take(2)->implode('') }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="truncate font-semibold text-slate-800">{{ $row['student']->name }}</p>
                                                    <p class="text-xs text-slate-400">No. Absen {{ $row['student']->attendanceLabel() }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-slate-500">{{ $row['student']->groups->first()?->name ?? '-' }}</td>
                                        <td class="px-5 py-3 text-right font-bold text-status-primary">{{ $row['points'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        @else
            @php $top3 = $groups->take(3)->values(); @endphp

            @if ($groups->isEmpty())
                <div class="panel-card p-8 text-center text-sm text-slate-400">
                    Belum ada kelompok di kelas ini.
                </div>
            @else
                {{-- Podium Top 3 kelompok --}}
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    @foreach ($top3 as $row)
                        @php $rank = $loop->iteration; @endphp
                        <div @class([
                            'panel-card relative p-5 text-center',
                            'ring-2 ring-amber-300 shadow-card-hover' => $rank === 1,
                        ])>
                            <div class="text-3xl">{{ ['🥇', '🥈', '🥉'][$rank - 1] ?? '' }}</div>
                            <p class="mt-3 font-bold text-slate-900">{{ $row['group']->name }}</p>
                            <p class="mt-0.5 text-xs text-slate-400">{{ $row['group']->members_count }} anggota</p>
                            <p class="mt-2 text-lg font-bold text-status-primary">{{ $row['points'] }} <span class="text-xs font-semibold text-slate-400">poin</span></p>
                        </div>
                    @endforeach
                </div>

                {{-- Daftar kelompok + anggota --}}
                <div class="space-y-3">
                    @foreach ($groups as $row)
                        <div class="panel-card p-4 sm:p-5" x-data="{ open: false }">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span @class([
                                        'flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm font-bold',
                                        'bg-amber-100 text-amber-700' => $loop->iteration === 1,
                                        'bg-slate-200 text-slate-600' => $loop->iteration === 2,
                                        'bg-orange-100 text-orange-700' => $loop->iteration === 3,
                                        'bg-slate-100 text-slate-500' => $loop->iteration > 3,
                                    ])>
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate font-bold text-slate-900">{{ $row['group']->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $row['group']->members_count }} anggota</p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-3">
                                    <p class="text-right font-bold text-status-primary">{{ $row['points'] }} <span class="text-xs font-semibold text-slate-400">poin</span></p>
                                    <button type="button" @click="open = ! open" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                                        <span x-text="open ? 'Tutup' : 'Anggota'"></span>
                                    </button>
                                </div>
                            </div>

                            <div x-show="open" x-cloak x-transition class="mt-4 border-t border-slate-100 pt-3">
                                @if ($row['group']->members->isEmpty())
                                    <p class="text-sm text-slate-400">Belum ada anggota.</p>
                                @else
                                    <ul class="divide-y divide-slate-100">
                                        @foreach ($row['group']->members as $member)
                                            <li class="flex items-center justify-between gap-3 py-2 text-sm">
                                                <span class="font-medium text-slate-700">{{ $member->name }}</span>
                                                <span class="font-mono text-xs font-semibold text-slate-400">No. Absen {{ $member->attendanceLabel() }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>
