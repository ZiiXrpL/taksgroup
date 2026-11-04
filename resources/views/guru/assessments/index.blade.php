<x-layouts.app title="Daftar Penilaian" role="guru">
    <div class="space-y-4">

        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <h3 class="text-xl font-bold text-slate-900">Daftar Penilaian</h3>
            <p class="mt-1 text-sm text-slate-500">
                Seluruh kelompok dan siswa yang sudah mengumpulkan tugas Anda, baik yang masih
                menunggu review maupun yang sudah dinilai sebelumnya.
            </p>
        </div>

        <form method="GET" action="{{ route('guru.assessments.index') }}" class="flex flex-wrap gap-2">
            <div class="w-full max-w-xs">
                <x-input type="text" name="search" value="{{ $search }}" placeholder="Cari judul tugas atau kelompok..." />
            </div>

            <div class="w-full sm:w-52">
                <x-select name="status">
                    <option value="">Menunggu Review &amp; Sudah Dinilai</option>
                    <option value="submitted" @selected($status === 'submitted')>Menunggu Review Saja</option>
                    <option value="completed" @selected($status === 'completed')>Sudah Dinilai Saja</option>
                </x-select>
            </div>

            <x-primary-button type="submit" class="shrink-0">Filter</x-primary-button>

            @if ($search !== '' || $status !== '')
                <a href="{{ route('guru.assessments.index') }}" class="inline-flex items-center rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-500 hover:bg-gray-100">
                    Reset
                </a>
            @endif
        </form>

        @if ($rows->isEmpty())
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-sm text-slate-400 shadow-sm">
                Belum ada kelompok atau siswa yang perlu dinilai. Tugas akan muncul di sini setelah
                dikumpulkan untuk review.
            </div>
        @else
            <div class="space-y-3">
                @foreach ($rows as $row)
                    <details class="group overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm" @if ($row->status === \App\Models\TaskGroup::STATUS_SUBMITTED || $errors->any()) open @endif>
                        <summary class="flex cursor-pointer list-none flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $row->group?->name ?? $row->student?->name ?? 'Penerima tugas' }}</p>
                                <p class="text-xs text-slate-400">
                                    @if ($row->group)
                                        {{ $row->group->schoolClass?->name }} &middot;
                                    @else
                                        Individu &middot;
                                    @endif
                                    {{ $row->task->title }}
                                    @if ($row->submitted_at)
                                        &middot; Dikumpulkan {{ $row->submitted_at->diffForHumans() }}
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                @if ($row->status === \App\Models\TaskGroup::STATUS_COMPLETED)
                                    @if ($row->student_id)
                                        <x-badge color="green">Selesai</x-badge>
                                    @else
                                        <x-badge color="green">Nilai: {{ $row->score }}</x-badge>
                                    @endif
                                @else
                                    <x-badge color="yellow">Menunggu Review</x-badge>
                                @endif
                                <span class="text-xs text-slate-400 group-open:hidden">Klik untuk {{ $row->status === \App\Models\TaskGroup::STATUS_COMPLETED ? 'ubah nilai' : 'beri nilai' }}</span>
                            </div>
                        </summary>

                        <div class="border-t border-gray-100 px-5 py-4">
                            @if ($row->student_id)
                                <a href="{{ route('guru.tasks.students.show', [$row->task, $row->student]) }}" class="inline-flex text-sm font-semibold text-status-primary hover:underline">
                                    Review detail individu &rarr;
                                </a>
                            @else
                            <form method="POST" action="{{ route('guru.tasks.groups.assessment.update', [$row->task, $row->group]) }}" class="space-y-3">
                                @csrf
                                @method('PUT')

                                <div class="grid gap-4 sm:grid-cols-4">
                                    <div class="sm:col-span-1">
                                        <x-label value="Nilai (0-100)" />
                                        <x-input type="number" name="score" min="0" max="100" value="{{ old('score', $row->score) }}" required />
                                        <x-input-error :messages="$errors->get('score')" class="mt-1" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <x-label value="Feedback" />
                                        <x-textarea name="feedback" rows="2" placeholder="Tuliskan masukan untuk kelompok ini...">{{ old('feedback', $row->feedback) }}</x-textarea>
                                        <x-input-error :messages="$errors->get('feedback')" class="mt-1" />
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <x-primary-button>Simpan Penilaian</x-primary-button>
                                    <a href="{{ route('guru.tasks.groups.show', [$row->task, $row->group]) }}" class="text-sm font-semibold text-status-primary hover:underline">
                                        Lihat detail lengkap &rarr;
                                    </a>
                                </div>
                            </form>
                            @endif
                        </div>
                    </details>
                @endforeach
            </div>

            {{ $rows->links() }}
        @endif
    </div>
</x-layouts.app>
