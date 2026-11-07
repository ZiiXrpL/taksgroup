<x-layouts.app title="Detail Tugas" role="siswa">
    <div class="space-y-6">

        {{-- Header Tugas --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-sm backdrop-blur-sm sm:p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <x-badge color="blue">{{ $task->subject }}</x-badge>
                    <span class="text-sm text-slate-400">Guru: {{ $task->teacher->name }}</span>
                </div>
                <x-deadline-badge :deadline="$task->deadline" />
            </div>

            <h3 class="mt-3 text-xl font-bold text-slate-900">{{ $task->title }}</h3>

            @if ($task->description)
                <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $task->description }}</p>
            @endif

            <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-badge :color="\App\Models\TaskGroup::statusColor($taskGroup->status)">
                    {{ \App\Models\TaskGroup::statusLabel($taskGroup->status) }}
                </x-badge>

                @if ($taskGroup->score !== null)
                    <x-badge color="green">Nilai: {{ $taskGroup->score }}</x-badge>
                @endif
            </div>

            @if ($taskGroup->feedback)
                <div class="mt-5 rounded-xl border border-cyan-200 bg-cyan-50/80 px-4 py-3.5 text-sm text-cyan-800">
                    <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-cyan-600">Feedback Guru</p>
                    <p class="leading-relaxed">{{ $taskGroup->feedback }}</p>
                </div>
            @endif

            @if ($taskGroup->status === \App\Models\TaskGroup::STATUS_SUBMITTED)
                <p class="mt-4 rounded-xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm font-medium text-cyan-800">Tugas sudah dikumpulkan dan sedang menunggu konfirmasi guru.</p>
            @elseif ($taskGroup->status === \App\Models\TaskGroup::STATUS_COMPLETED)
                <p class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">Tugas telah dikonfirmasi guru dan selesai.</p>
            @endif

            <div class="mt-6">
                <div class="mb-2 flex items-center justify-between text-sm">
                    <span class="font-semibold text-slate-700">Progres Pengerjaan {{ $isIndividual ? 'Individu' : 'Kelompok' }}</span>
                    <span class="font-bold text-amber-600">{{ $taskGroup->progress }}% Selesai</span>
                </div>
                <x-progress-bar :value="$taskGroup->progress" :show-label="false" />
                <p class="mt-1.5 text-xs text-slate-400">Progress bertambah setelah guru mengkonfirmasi bukti pengerjaan subtask.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Checklist Subtugas dengan Upload Bukti --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-sm backdrop-blur-sm sm:p-6">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-slate-900">Checklist Subtugas</h3>
                    <p class="mt-1 text-xs text-slate-400">Upload bukti file pengerjaan untuk setiap subtask. Guru akan mengkonfirmasi sebelum progress bertambah.</p>
                </div>

                @if ($task->subtasks->isEmpty())
                    <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h1M7 4h10a1 1 0 011 1v14a1 1 0 01-1 1H7a1 1 0 01-1-1V5a1 1 0 011-1z"/></svg>
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
                            <div class="rounded-xl border {{ $isConfirmed ? 'border-emerald-200 bg-emerald-50/50' : ($isPending ? 'border-amber-200 bg-amber-50/50' : ($isRejected ? 'border-red-200 bg-red-50/50' : 'border-slate-200 bg-slate-50/60')) }} p-4 transition-all">
                                <div class="flex items-start gap-3">
                                    {{-- Status Icon --}}
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

                                        {{-- Status badge --}}
                                        @if ($completion && $status)
                                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                                <x-badge :color="\App\Models\SubtaskCompletion::statusColor($status)">
                                                    {{ \App\Models\SubtaskCompletion::statusLabel($status) }}
                                                </x-badge>
                                                @if ($completion->proof_file_name)
                                                    <a href="{{ route('proofs.download', $completion) }}" class="inline-flex items-center gap-1 text-xs font-medium text-status-primary hover:underline">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v13a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                                                        {{ $completion->proof_file_name }}
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Pesan penolakan --}}
                                        @if ($isRejected && $completion->rejection_note)
                                            <div class="mt-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-700">
                                                <span class="font-semibold">Catatan Guru:</span> {{ $completion->rejection_note }}
                                            </div>
                                        @endif

                                        {{-- Form Upload Bukti (tampil jika belum upload / ditolak) --}}
                                        @if (!$isConfirmed && !$isPending)
                                            <form
                                                method="POST"
                                                action="{{ route('siswa.tasks.checklist.submit-proof', [$task, $subtask]) }}"
                                                enctype="multipart/form-data"
                                                class="mt-3"
                                                x-data="{
                                                    fileName: '',
                                                    error: '',
                                                    allowed: @json(\App\Support\UploadRules::EXTENSIONS),
                                                    maxBytes: @json(\App\Support\UploadRules::MAX_BYTES),
                                                    check(file) {
                                                        if (!file) { this.fileName = ''; this.error = ''; return; }
                                                        const ext = (file.name.split('.').pop() || '').toLowerCase();
                                                        if (file.size > this.maxBytes) {
                                                            this.error = 'Ukuran file maksimal 5 MB.';
                                                            this.fileName = '';
                                                            this.$refs.proofInput.value = '';
                                                            return;
                                                        }
                                                        if (!this.allowed.includes(ext)) {
                                                            this.error = 'Format file tidak didukung.';
                                                            this.fileName = '';
                                                            this.$refs.proofInput.value = '';
                                                            return;
                                                        }
                                                        this.error = '';
                                                        this.fileName = file.name;
                                                    }
                                                }"
                                            >
                                                @csrf
                                                <div class="flex flex-col gap-2 sm:flex-row sm:items-end">
                                                    <div class="flex-1">
                                                        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-dashed border-slate-300 bg-white px-3 py-2.5 text-sm transition hover:border-status-primary">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
                                                            <span class="text-slate-500">
                                                                <span x-show="!fileName">Pilih file bukti...</span>
                                                                <span x-show="fileName" x-text="fileName" class="font-medium text-slate-700"></span>
                                                                <input type="file" name="proof_file" x-ref="proofInput" accept="{{ \App\Support\UploadRules::acceptAttribute() }}" required class="hidden" @change="check($event.target.files[0])">
                                                            </span>
                                                        </label>
                                                        <p x-show="error" x-text="error" x-cloak class="mt-1 text-[10px] font-medium text-red-500"></p>
                                                    </div>
                                                    <button type="submit" class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-lg bg-status-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                        Kirim Bukti
                                                    </button>
                                                </div>
                                                <p class="mt-1 text-[10px] text-slate-400">Maks. 5 MB · {{ \App\Support\UploadRules::label() }}</p>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                {{-- Upload file hasil tugas --}}
                <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-5 shadow-sm backdrop-blur-sm sm:p-6">
                    <div class="mb-4">
                        <h3 class="text-base font-bold text-slate-900">Upload Dokumen Tugas</h3>
                        <p class="mt-1 text-xs text-slate-400">Upload dokumen hasil tugas {{ $isIndividual ? 'individu' : 'kelompok' }} (PDF, Word, Excel, PPT, dll).</p>
                    </div>

                    @if (! in_array($taskGroup->status, [\App\Models\TaskGroup::STATUS_SUBMITTED, \App\Models\TaskGroup::STATUS_COMPLETED], true))
                        <form
                            method="POST"
                            action="{{ route('siswa.files.store', $task) }}"
                            enctype="multipart/form-data"
                            class="space-y-3"
                        >
                            @csrf
                            <div x-data="{
                                fileName: '',
                                error: '',
                                allowed: @json(\App\Support\UploadRules::EXTENSIONS),
                                maxBytes: @json(\App\Support\UploadRules::MAX_BYTES),
                                check(file) {
                                    if (!file) { this.fileName = ''; this.error = ''; return; }
                                    const ext = (file.name.split('.').pop() || '').toLowerCase();
                                    if (file.size > this.maxBytes) {
                                        this.error = 'Ukuran file maksimal 5 MB.';
                                        this.fileName = '';
                                        this.$refs.docInput.value = '';
                                        return;
                                    }
                                    if (!this.allowed.includes(ext)) {
                                        this.error = 'Format file tidak didukung.';
                                        this.fileName = '';
                                        this.$refs.docInput.value = '';
                                        return;
                                    }
                                    this.error = '';
                                    this.fileName = file.name;
                                }
                            }">
                                <label class="flex cursor-pointer flex-col items-center justify-center gap-2.5 rounded-xl border-2 border-dashed border-slate-200 bg-slate-50/60 px-4 py-8 text-center transition hover:border-status-primary hover:bg-blue-50/30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-status-primary/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                                    </svg>
                                    <span x-show="!fileName" class="text-sm font-semibold text-status-primary">Pilih file atau drag &amp; drop disini</span>
                                    <span x-show="fileName" x-text="fileName" class="text-sm font-semibold text-slate-800"></span>
                                    <span class="text-xs text-slate-400">Maks 5 MB · {{ \App\Support\UploadRules::label() }}</span>
                                    <input type="file" name="file" x-ref="docInput" accept="{{ \App\Support\UploadRules::acceptAttribute() }}" required class="hidden" @change="check($event.target.files[0])">
                                </label>
                                <p x-show="error" x-text="error" x-cloak class="mt-2 text-xs font-medium text-red-500"></p>
                            </div>
                            <x-input-error :messages="$errors->get('file')" />
                            <x-primary-button class="w-full justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0l-4 4m4-4l4 4M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/></svg>
                                Upload Dokumen
                            </x-primary-button>
                        </form>
                    @else
                        <p class="rounded-lg bg-slate-50 px-3 py-2 text-sm text-slate-500">Upload dokumen ditutup setelah tugas dikumpulkan.</p>
                    @endif

                    @if (! in_array($taskGroup->status, [\App\Models\TaskGroup::STATUS_SUBMITTED, \App\Models\TaskGroup::STATUS_COMPLETED], true) && ($taskGroup->progress >= 100 || $task->subtasks->isEmpty()))
                        <form method="POST" action="{{ route('siswa.tasks.submit', $task) }}" class="mt-4">
                            @csrf
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                Kumpulkan Tugas
                            </button>
                        </form>
                    @elseif (! in_array($taskGroup->status, [\App\Models\TaskGroup::STATUS_SUBMITTED, \App\Models\TaskGroup::STATUS_COMPLETED], true))
                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-700">
                            Semua checklist harus selesai atau progres harus 100% sebelum tugas dapat dikumpulkan.
                        </div>
                    @endif

                    @if ($files->isNotEmpty())
                        <ul class="mt-5 space-y-2 border-t border-slate-100 pt-4">
                            @foreach ($files as $file)
                                <li class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-slate-50/60 px-4 py-3 text-sm transition hover:bg-slate-50">
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
                                            <span class="block truncate font-medium text-slate-800">{{ $file->file_name }}</span>
                                            <span class="text-xs text-slate-400">{{ $file->formatted_size }} · {{ $file->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="flex shrink-0 items-center gap-2">
                                        <a href="{{ route('files.download', $file) }}" class="rounded-lg px-2.5 py-1.5 text-xs font-semibold text-status-primary transition hover:bg-indigo-50">Unduh</a>
                                        @if ($file->uploaded_by === auth()->id() && !in_array($taskGroup->status, ['submitted', 'completed']))
                                            <form method="POST" action="{{ route('siswa.files.destroy', $file) }}" onsubmit="return confirm('Hapus file ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg p-1.5 text-slate-400 transition hover:bg-red-50 hover:text-red-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Komentar --}}
                @if (! $isIndividual)
                    <x-comment-thread :comments="$comments" :action="route('siswa.tasks.comments.store', $task)" />
                @endif
            </div>
        </div>

        <a href="{{ route('siswa.tasks.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke daftar tugas
        </a>
    </div>
</x-layouts.app>
