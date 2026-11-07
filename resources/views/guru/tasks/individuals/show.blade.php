<x-layouts.app title="Review Tugas Individu" role="guru">
    <div class="space-y-6">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-400">Review individu</p>
                    <h3 class="mt-1 text-xl font-bold text-slate-900">{{ $student->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500">{{ $student->email }} · Nomor absen {{ $student->attendanceLabel() }} · {{ $task->title }}</p>
                </div>
                <x-badge :color="\App\Models\TaskGroup::statusColor($assignment->status)">{{ \App\Models\TaskGroup::statusLabel($assignment->status) }}</x-badge>
            </div>
            <div class="mt-5 flex items-center gap-3">
                <x-progress-bar :value="$assignment->progress" class="flex-1" />
                <span class="text-sm font-bold text-slate-700">{{ $assignment->progress }}%</span>
            </div>
            @if (in_array($assignment->status, [\App\Models\TaskGroup::STATUS_SUBMITTED, \App\Models\TaskGroup::STATUS_COMPLETED], true))
                <form method="POST" action="{{ route('guru.tasks.students.assessment.update', [$task, $student]) }}" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')
                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <x-label for="score" value="Nilai (0-100)" />
                            <x-input id="score" name="score" type="number" min="0" max="100" value="{{ old('score', $assignment->score) }}" required />
                            <x-input-error :messages="$errors->get('score')" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-label for="feedback" value="Feedback" />
                            <x-textarea id="feedback" name="feedback" rows="2">{{ old('feedback', $assignment->feedback) }}</x-textarea>
                            <x-input-error :messages="$errors->get('feedback')" />
                        </div>
                    </div>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                        {{ $assignment->status === \App\Models\TaskGroup::STATUS_COMPLETED ? 'Perbarui Nilai' : 'Simpan Nilai & Konfirmasi' }}
                    </button>
                </form>
            @endif
            <a href="{{ route('guru.tasks.show', $task) }}" class="mt-5 inline-flex text-sm font-semibold text-slate-500 hover:text-slate-900">Kembali ke detail tugas</a>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-4">
                <h3 class="text-base font-bold text-slate-900">Bukti Checklist</h3>
                <p class="mt-1 text-xs text-slate-500">Progres siswa bertambah setelah bukti dikonfirmasi.</p>
            </div>
            <div class="space-y-3">
                @forelse ($task->subtasks as $subtask)
                    @php($completion = $subtaskCompletions->get($subtask->id))
                    @php($status = $completion?->status ?? ($completion?->proof_file_path ? \App\Models\SubtaskCompletion::STATUS_PENDING : null))
                    <article class="rounded-lg border border-slate-200 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $loop->iteration }}. {{ $subtask->title }}</p>
                                @if ($subtask->description)<p class="mt-1 text-sm text-slate-500">{{ $subtask->description }}</p>@endif
                            </div>
                            @if ($status)<x-badge :color="\App\Models\SubtaskCompletion::statusColor($status)">{{ \App\Models\SubtaskCompletion::statusLabel($status) }}</x-badge>@else<x-badge color="gray">Belum mengirim bukti</x-badge>@endif
                        </div>
                        @if ($completion?->proof_file_name)
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                <a href="{{ route('proofs.download', $completion) }}" class="truncate text-sm font-medium text-status-primary hover:underline">{{ $completion->proof_file_name }}</a>
                                @if ($status === \App\Models\SubtaskCompletion::STATUS_PENDING)
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('guru.tasks.students.subtask.confirm', [$task, $student, $completion]) }}">@csrf @method('PATCH')<button class="rounded-lg bg-emerald-600 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-700">Konfirmasi</button></form>
                                        <form method="POST" action="{{ route('guru.tasks.students.subtask.reject', [$task, $student, $completion]) }}">@csrf @method('PATCH')<input type="hidden" name="rejection_note" value="Perlu diperbaiki"><button class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">Tolak</button></form>
                                    </div>
                                @endif
                            </div>
                        @elseif ($completion?->rejection_note)
                            <p class="mt-3 rounded-lg bg-red-50 p-3 text-sm text-red-700">Catatan: {{ $completion->rejection_note }}</p>
                        @endif
                    </article>
                @empty
                    <p class="py-8 text-center text-sm text-slate-400">Tugas ini belum memiliki checklist.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h3 class="mb-3 text-base font-bold text-slate-900">Dokumen yang dikirim</h3>
            @forelse ($files as $file)
                <div class="flex items-center justify-between gap-3 border-t border-slate-100 py-3 text-sm">
                    <span class="truncate text-slate-700">{{ $file->file_name }} · {{ $file->formatted_size }}</span>
                    <a href="{{ route('files.download', $file) }}" class="shrink-0 font-semibold text-status-primary hover:underline">Unduh</a>
                </div>
            @empty
                <p class="py-5 text-sm text-slate-400">Belum ada dokumen tugas yang dikirim.</p>
            @endforelse
        </section>
    </div>
</x-layouts.app>