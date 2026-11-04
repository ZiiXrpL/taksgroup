<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Progres - {{ config('app.name', 'TaskGroup') }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { padding: 0 !important; }
            .table-scroll { overflow: visible !important; }
            .table-scroll table { min-width: 0 !important; width: 100% !important; }
        }
    </style>
</head>
<body class="bg-white p-4 text-gray-900 antialiased sm:p-8">
    <div class="no-print mb-6 flex justify-end gap-2">
        <button onclick="window.print()" class="rounded-xl bg-status-primary px-4 py-2 text-sm font-semibold text-white shadow">
            Cetak / Simpan sebagai PDF
        </button>
    </div>

    <div class="mb-6 border-b border-gray-300 pb-4">
        <h1 class="text-xl font-bold">Laporan Progres Tugas Kelompok</h1>
        <p class="text-sm text-gray-500">
            Guru: {{ $teacher->name }} &middot; Dicetak {{ $printedAt->translatedFormat('d F Y, H:i') }}
        </p>
    </div>

    @if ($tasks->isEmpty())
        <p class="text-sm text-gray-500">Tidak ada data untuk filter yang dipilih.</p>
    @else
        <div class="space-y-8">
            @foreach ($tasks as $task)
                <div>
                    <h2 class="font-semibold">{{ $task->title }}</h2>
                    <p class="mb-2 text-xs text-gray-500">
                        {{ $task->subject }} &middot; Deadline {{ $task->deadline?->translatedFormat('d M Y') ?? '-' }}
                    </p>

                    <div class="table-scroll overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-300 text-left">
                                <th class="py-1.5 pr-4">Kelompok</th>
                                <th class="py-1.5 pr-4">Kelas</th>
                                <th class="py-1.5 pr-4">Progress</th>
                                <th class="py-1.5 pr-4">Status</th>
                                <th class="py-1.5 pr-4">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($task->groups as $group)
                                <tr class="border-b border-gray-100">
                                    <td class="py-1.5 pr-4">{{ $group->name }}</td>
                                    <td class="py-1.5 pr-4">{{ $group->schoolClass?->name ?? '-' }}</td>
                                    <td class="py-1.5 pr-4">{{ $group->pivot->progress }}%</td>
                                    <td class="py-1.5 pr-4">{{ \App\Models\TaskGroup::statusLabel($group->pivot->status) }}</td>
                                    <td class="py-1.5 pr-4">{{ $group->pivot->score ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
