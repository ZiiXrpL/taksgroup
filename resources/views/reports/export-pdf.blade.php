<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>{{ $title }}</title>
<style>
    @page { margin: 14mm 12mm 18mm 12mm; }
    body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #1e293b; }
    h1 { font-size: 18px; margin: 0 0 2px; color: #4f46e5; }
    .brand { font-size: 11px; font-weight: bold; color: #334155; letter-spacing: .5px; }
    .subtitle { font-size: 10px; color: #64748b; margin-bottom: 14px; }
    .task { margin-top: 14px; page-break-inside: avoid; }
    .task-title { font-size: 13px; font-weight: bold; color: #0f172a; }
    .task-meta { font-size: 9px; color: #64748b; margin-bottom: 4px; }
    .group { margin-top: 8px; }
    .group-title { font-size: 11px; font-weight: bold; color: #4f46e5; }
    .group-meta { font-size: 9px; color: #64748b; margin-bottom: 4px; }
    table { width: 100%; border-collapse: collapse; margin-top: 4px; }
    th, td { border: 1px solid #cbd5e1; padding: 3px 5px; text-align: left; }
    th { background: #eef2ff; font-size: 9px; }
    td { font-size: 9px; }
    .empty { font-size: 10px; color: #64748b; }
</style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="subtitle">{{ $subtitle }} &middot; Diekspor {{ $printedAt->translatedFormat('d F Y, H:i') }}</div>

    @forelse ($rows as $data)
        <div class="task">
            <div class="task-title">{{ $data['task']->title }}</div>
            <div class="task-meta">
                Mata Pelajaran: {{ $data['task']->subject }} &middot;
                Deadline: {{ $data['task']->deadline?->translatedFormat('d M Y') ?? '-' }}
            </div>

            <div class="group">
                <div class="group-title">KELOMPOK: {{ $data['group']->name }}</div>
                <div class="group-meta">
                    Kelas: {{ $data['group']->schoolClass?->name ?? '-' }} &middot;
                    Progress: {{ $data['progress'] }}% &middot;
                    Status: {{ \App\Models\TaskGroup::statusLabel($data['status']) }} &middot;
                    Nilai: {{ $data['score'] ?? '-' }}
                    @if ($data['feedback'])
                        &middot; Feedback: {{ $data['feedback'] }}
                    @endif
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 6%">No</th>
                            <th style="width: 42%">Nama Siswa</th>
                            <th style="width: 16%">Nomor Absen</th>
                            <th style="width: 36%">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data['members'] as $member)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->attendanceLabel() }}</td>
                                <td>{{ $member->schoolClass?->name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Belum ada anggota.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <p class="empty">Tidak ada data untuk filter yang dipilih.</p>
    @endforelse
</body>
</html>
