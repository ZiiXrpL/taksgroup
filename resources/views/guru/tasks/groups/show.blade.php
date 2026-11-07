<x-layouts.app title="Detail Progres Kelompok" role="guru">
    <div class="space-y-6">

        {{-- Header Info --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-sm backdrop-blur-sm sm:p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Tugas</p>
                    <h3 class="mt-1 text-lg font-bold text-slate-900">{{ $task->title }}</h3>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Kelompok / Kelas</p>
                    <p class="mt-1 text-base font-bold text-slate-900">{{ $group->name }} <span class="font-normal text-slate-400">({{ $group->schoolClass?->name }})</span></p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Ketua Kelompok</p>
                    <p class="mt-1 text-base font-bold text-slate-900">{{ $group->leader?->name ?? '-' }}</p>
                </div>
                <div class="flex items-start justify-start sm:justify-end">
                    <x-badge :color="\App\Models\TaskGroup::statusColor($taskGroup->status)" class="shrink-0">
                        {{ \App\Models\TaskGroup::statusLabel($taskGroup->status) }}
                    </x-badge>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach ($group->members as $member)
                    <x-badge color="gray">{{ $member->name }} · {{ $member->attendanceLabel() }}</x-badge>
                @endforeach
            </div>

            <div class="mt-5">
                <div class="mb-2 flex items-center justify-between text-sm">
                    <span class="font-semibold text-slate-700">Progres Penyelesaian Tugas</span>
                    <span class="font-bold text-status-primary">{{ $taskGroup->progress }}% Selesai</span>
                </div>
                <x-progress-bar :value="$taskGroup->progress" :show-label="false" />
                <p class="mt-1.5 text-xs text-slate-400">Progress dihitung dari subtask yang sudah Anda konfirmasi.</p>
            </div>

            <div class="mt-5">
                <a href="{{ route('guru.tasks.show', $task) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke detail tugas
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Checklist dengan konfirmasi --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-sm backdrop-blur-sm sm:p-6">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Checklist Subtugas</h3>
                    <p class="mt-1 text-xs text-slate-400">Konfirmasi atau tolak bukti pengerjaan yang dikirim siswa. Progress hanya bertambah setelah dikonfirmasi.</p>
                </div>

                @if ($task->subtasks->isEmpty())
                    <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 py-8">
                        <p class="text-sm text-slate-400">Tugas ini belum memiliki checklist.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($task->subtasks as $subtask)
                            @php
                                $completion = $subtaskCompletions->get($subtask->id);
                                $status = $completion?->status ?? ($completion?->proof_file_path ? \App\Models\SubtaskCompletion::STATUS_PENDING : null);
                                $isConfirmed = $status === \App\Models\SubtaskCompletion::STATUS_CONFIRMED;
                                $isPending = $status === \App\Models\SubtaskCompletion::STATUS_PENDING;
                                $isRejected = $status === \App\Models\SubtaskCompletion::STATUS_REJECTED;
                            @endphp
                            <div class="rounded-xl border {{ $isConfirmed ? 'border-emerald-200 bg-emerald-50/50' : ($isPending ? 'border-amber-200 bg-amber-50/50' : ($isRejected ? 'border-red-200 bg-red-50/50' : 'border-slate-200 bg-slate-50/60')) }} p-4">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full {{ $isConfirmed ? 'bg-emerald-500 text-white' : ($isPending ? 'bg-amber-400 text-white' : ($isRejected ? 'bg-red-400 text-white' : 'border-2 border-slate-300 bg-white')) }}">
                                        @if ($isConfirmed)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.9 3.9 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                        @elseif ($isPending)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                        @elseif ($isRejected)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        @else
                                            <span class="text-xs font-bold text-slate-400">{{ $loop->iteration }}</span>
                                        @endif
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium {{ $isConfirmed ? 'text-emerald-800 line-through' : 'text-slate-800' }}">
                                            {{ $subtask->title }}
                                        </p>
                                        @if ($subtask->description)
                                            <p class="mt-0.5 text-xs text-slate-400">{{ $subtask->description }}</p>
                                        @endif

                                        {{-- Completion info --}}
                                        @if ($completion && $status)
                                            <div class="mt-2 space-y-2">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <x-badge :color="\App\Models\SubtaskCompletion::statusColor($status)">
                                                        {{ \App\Models\SubtaskCompletion::statusLabel($status) }}
                                                    </x-badge>
                                                    <span class="text-xs text-slate-400">
                                                        oleh {{ $completion->completedBy?->name ?? 'Siswa' }} · {{ $completion->completed_at->diffForHumans() }}
                                                    </span>
                                                </div>

                                                @if ($completion->proof_file_name)
                                                    <a href="{{ route('proofs.download', $completion) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-status-primary transition hover:bg-indigo-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        Unduh Bukti: {{ $completion->proof_file_name }}
                                                    </a>
                                                @endif

                                                {{-- Tombol Konfirmasi / Tolak untuk guru --}}
                                                @if ($isPending)
                                                    <div class="flex flex-wrap gap-2 pt-1" x-data="{ showReject: false }">
                                                        <form method="POST" action="{{ route('guru.tasks.groups.subtask.confirm', [$task, $group, $completion]) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-600">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.9 3.9 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                                                                Konfirmasi
                                                            </button>
                                                        </form>

                                                        <button type="button" @click="showReject = !showReject" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-white px-3.5 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                                            Tolak
                                                        </button>

                                                        <form method="POST" action="{{ route('guru.tasks.groups.subtask.reject', [$task, $group, $completion]) }}" x-show="showReject" x-cloak class="w-full" x-transition>
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="mt-1 flex gap-2">
                                                                <input type="text" name="rejection_note" placeholder="Alasan penolakan (opsional)..." class="block w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-status-primary focus:ring-status-primary">
                                                                <button type="submit" class="shrink-0 rounded-lg bg-red-500 px-3 py-2 text-xs font-semibold text-white hover:bg-red-600">Tolak</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                @endif

                                                @if ($isRejected && $completion->rejection_note)
                                                    <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700">
                                                        <span class="font-semibold">Alasan penolakan:</span> {{ $completion->rejection_note }}
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif ($completion)
                                            <p class="mt-2 text-xs italic text-slate-400">Belum ada bukti pengerjaan yang dapat diperiksa.</p>
                                        @else
                                            <p class="mt-2 text-xs italic text-slate-400">Siswa belum mengirim bukti pengerjaan.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Komentar --}}
            <x-comment-thread :comments="$comments" :action="route('guru.tasks.groups.comments.store', [$task, $group])" />
        </div>

        {{-- File hasil tugas kelompok ini --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 shadow-sm backdrop-blur-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900">File Hasil Tugas</h3>
                <p class="mt-0.5 text-xs text-slate-400">Dokumen yang diupload oleh anggota kelompok.</p>
            </div>

            @if ($files->isEmpty())
                <div class="flex flex-col items-center justify-center px-6 py-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                    <p class="text-sm text-slate-400">Belum ada file yang diupload kelompok ini.</p>
                </div>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($files as $file)
                        <li class="flex items-center justify-between gap-3 px-6 py-3.5 transition hover:bg-slate-50/60">
                            <div class="flex min-w-0 items-center gap-3">
                                @php
                                    $iconColor = match(true) {
                                        str_contains($file->file_type, 'pdf') => 'text-red-500',
                                        str_contains($file->file_type, 'word') || str_contains($file->file_type, 'document') => 'text-blue-500',
                                        str_contains($file->file_type, 'sheet') || str_contains($file->file_type, 'excel') => 'text-green-500',
                                        str_contains($file->file_type, 'image') => 'text-purple-500',
                                        default => 'text-slate-400',
                                    };
                                @endphp
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 {{ $iconColor }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-800">{{ $file->file_name }}</p>
                                    <p class="text-xs text-slate-400">
                                        {{ $file->uploader?->name }} &middot; {{ $file->formatted_size }} &middot; {{ $file->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('files.download', $file) }}" class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-semibold text-status-primary transition hover:bg-indigo-50">
                                Unduh
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Penilaian --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 shadow-sm backdrop-blur-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900">Feedback &amp; Nilai</h3>
                <p class="mt-0.5 text-xs text-slate-400">
                    @if ($taskGroup->status === \App\Models\TaskGroup::STATUS_SUBMITTED)
                        Kelompok ini sudah mengumpulkan tugas dan menunggu penilaian Anda.
                    @elseif ($taskGroup->status === \App\Models\TaskGroup::STATUS_COMPLETED)
                        Tugas kelompok ini sudah dinilai. Anda dapat memperbarui nilai/feedback di bawah.
                    @else
                        Nilai dapat diberikan setelah kelompok mengumpulkan seluruh checklist.
                    @endif
                </p>
            </div>

            <div class="px-6 py-5">
                @if (! in_array($taskGroup->status, [\App\Models\TaskGroup::STATUS_SUBMITTED, \App\Models\TaskGroup::STATUS_COMPLETED], true))
                    <div class="flex flex-col items-center justify-center py-4">
                        <p class="text-sm text-slate-400">Belum dapat dinilai — kelompok belum mengumpulkan tugas.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('guru.tasks.groups.assessment.update', [$task, $group]) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-4 sm:grid-cols-4">
                            <div class="sm:col-span-1">
                                <x-label for="score" value="Nilai (0-100)" />
                                <x-input
                                    id="score"
                                    name="score"
                                    type="number"
                                    min="0"
                                    max="100"
                                    value="{{ old('score', $taskGroup->score) }}"
                                    required
                                />
                                <x-input-error :messages="$errors->get('score')" class="mt-1" />
                            </div>

                            <div class="sm:col-span-3">
                                <x-label for="feedback" value="Feedback" />
                                <x-textarea id="feedback" name="feedback" rows="3" placeholder="Tuliskan masukan untuk kelompok ini...">{{ old('feedback', $taskGroup->feedback) }}</x-textarea>
                                <x-input-error :messages="$errors->get('feedback')" class="mt-1" />
                            </div>
                        </div>

                        <x-primary-button>Simpan Penilaian</x-primary-button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Riwayat aktivitas --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white/90 shadow-sm backdrop-blur-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-bold text-slate-900">Riwayat Aktivitas</h3>
            </div>

            @if ($activities->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-slate-400">Belum ada aktivitas.</p>
            @else
                <ul class="divide-y divide-slate-100">
                    @foreach ($activities as $activity)
                        <li class="flex items-start gap-3 px-6 py-3.5">
                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-status-primary"></span>
                            <div>
                                <p class="text-sm text-slate-700">{{ $activity->description }}</p>
                                <p class="mt-0.5 text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-layouts.app>
