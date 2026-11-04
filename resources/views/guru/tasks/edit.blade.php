<x-layouts.app title="Edit Tugas" role="guru">
    <div class="space-y-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">Form Edit Tugas</h3>
            <p class="mt-1 text-sm text-slate-500">{{ $task->title }}</p>
        </div>

        <div class="max-w-2xl rounded-2xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
        <form
            method="POST"
            action="{{ route('guru.tasks.update', $task) }}"
            class="space-y-5"
            x-data="{ audienceType: {{ Illuminate\Support\Js::from(old('audience_type', $audienceType)) }}, studentSearch: '', classFilter: 'all', subtasks: {{ Illuminate\Support\Js::from(
                old('subtasks', $subtasks->map(fn ($s) => ['id' => $s->id, 'title' => $s->title, 'description' => $s->description])->all())
            ) }} }"
        >
            @csrf
            @method('PUT')

            <div>
                <x-label for="title" value="Judul Tugas" />
                <x-input id="title" name="title" type="text" value="{{ old('title', $task->title) }}" required autofocus />
                <x-input-error :messages="$errors->get('title')" />
            </div>

            <div>
                <x-label for="description" value="Deskripsi" />
                <x-textarea id="description" name="description">{{ old('description', $task->description) }}</x-textarea>
                <x-input-error :messages="$errors->get('description')" />
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <x-label for="subject" value="Mata Pelajaran" />
                    <x-input id="subject" name="subject" type="text" value="{{ old('subject', $task->subject) }}" required />
                    <x-input-error :messages="$errors->get('subject')" />
                </div>

                <div>
                    <x-label for="priority" value="Prioritas" />
                    <x-select id="priority" name="priority" required>
                        @foreach (['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('priority', $task->priority) === $value)>{{ $label }}</option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('priority')" />
                </div>
            </div>

            <div>
                <x-label for="deadline" value="Deadline" />
                <x-input id="deadline" name="deadline" type="datetime-local"
                    value="{{ old('deadline', $task->deadline->format('Y-m-d\TH:i')) }}" required />
                <x-input-error :messages="$errors->get('deadline')" />
            </div>

            <div>
                <x-label value="Penerima Tugas" />
                <p class="mb-3 text-xs text-slate-400">Mengganti penerima akan melepas progres dan file terkait penerima yang dihapus.</p>
                <div class="mb-4 grid grid-cols-2 gap-2 rounded-2xl border border-slate-200 bg-slate-50 p-2">
                    <label class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border px-3 py-3 text-sm font-semibold transition" :class="audienceType === 'groups' ? 'border-blue-200 bg-white text-blue-700 shadow-sm ring-1 ring-blue-100' : 'border-transparent text-slate-500 hover:bg-white hover:text-slate-700'">
                        <input type="radio" name="audience_type" value="groups" x-model="audienceType" class="sr-only"> Kelompok
                    </label>
                    <label class="flex cursor-pointer items-center justify-center gap-2 rounded-xl border px-3 py-3 text-sm font-semibold transition" :class="audienceType === 'students' ? 'border-blue-200 bg-white text-blue-700 shadow-sm ring-1 ring-blue-100' : 'border-transparent text-slate-500 hover:bg-white hover:text-slate-700'">
                        <input type="radio" name="audience_type" value="students" x-model="audienceType" class="sr-only"> Individu
                    </label>
                </div>

                <div x-show="audienceType === 'groups'" x-cloak>
                    <div class="mb-3">
                        <p class="text-sm font-semibold text-slate-800">Pilih kelompok penerima</p>
                        <p class="mt-0.5 text-xs text-slate-500">Centang satu atau beberapa kelompok.</p>
                    </div>
                    <div class="max-h-80 space-y-4 overflow-y-auto rounded-2xl border border-slate-200 bg-slate-50/70 p-3 sm:p-4">
                    @forelse ($groupsByClass as $className => $groups)
                        <div class="rounded-xl border border-slate-200 bg-white p-3">
                            <p class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-slate-600">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                {{ $className }}
                            </p>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach ($groups as $group)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50/50 has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 has-[:checked]:ring-1 has-[:checked]:ring-blue-200">
                                        <input
                                            type="checkbox"
                                            name="groups[]"
                                            value="{{ $group->id }}"
                                            class="h-4 w-4 rounded border-slate-300 text-status-primary focus:ring-status-primary"
                                            @checked(collect(old('groups', $selectedGroups))->contains($group->id))
                                        >
                                        <span class="font-medium">{{ $group->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">Belum ada kelompok yang terdaftar.</p>
                    @endforelse
                    </div>
                    <x-input-error :messages="$errors->get('groups')" />
                </div>

                <div x-show="audienceType === 'students'" x-cloak class="space-y-3">
                    <div class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(14rem,1fr)]">
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Cari siswa</span>
                            <x-input type="search" x-model="studentSearch" placeholder="Nama atau email siswa..." aria-label="Cari siswa" class="!border-slate-200 !bg-white !py-3 focus:!border-blue-400 focus:!ring-4 focus:!ring-blue-100" />
                        </label>
                        <label class="block">
                            <span class="mb-1.5 block text-xs font-semibold text-slate-600">Pilih kelas</span>
                            <select x-model="classFilter" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-700 shadow-sm transition hover:border-blue-300 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100" aria-label="Filter kelas">
                            <option value="all">Semua kelas</option>
                            @foreach ($studentsByClass as $classId => $students)
                                <option value="{{ $classId }}">{{ $students->first()->schoolClass?->displayLabel() ?? 'Belum memiliki kelas' }}</option>
                            @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="max-h-80 space-y-3 overflow-y-auto rounded-2xl border border-slate-200 bg-slate-50/70 p-3 sm:p-4">
                        @forelse ($studentsByClass as $classId => $students)
                            <div data-class="{{ $classId }}" x-show="classFilter === 'all' || classFilter === $el.dataset.class" class="rounded-xl border border-slate-200 bg-white p-3">
                                <p class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-wide text-slate-600">
                                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                                    {{ $students->first()->schoolClass?->displayLabel() ?? 'Belum memiliki kelas' }}
                                </p>
                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach ($students as $student)
                                        <label data-search="{{ strtolower($student->name.' '.$student->email) }}" x-show="!studentSearch || $el.dataset.search.includes(studentSearch.toLowerCase())" class="flex min-w-0 cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50/50 has-[:checked]:border-indigo-400 has-[:checked]:bg-indigo-50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-200">
                                            <input type="checkbox" name="students[]" value="{{ $student->id }}" class="mt-0.5 h-4 w-4 rounded border-slate-300 text-status-primary focus:ring-status-primary" @checked(collect(old('students', $selectedStudents))->contains($student->id))>
                                            <span class="min-w-0"><span class="block truncate font-medium">{{ $student->name }}</span><span class="mt-0.5 block truncate text-xs text-slate-500">{{ $student->email }}</span></span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Belum ada akun siswa.</p>
                        @endforelse
                    </div>
                    <x-input-error :messages="$errors->get('students')" />
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                            </svg>
                        </span>
                        <div>
                            <x-label value="Checklist / Subtask" />
                            <p class="mt-1 text-xs leading-relaxed text-slate-500">Bagi tugas menjadi langkah-langkah kecil agar progres lebih mudah dipantau.</p>
                        </div>
                    </div>
                    <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-500 ring-1 ring-slate-200">
                        <span x-text="subtasks.length"></span> langkah
                    </span>
                </div>
                <p class="mb-4 rounded-xl border border-amber-100 bg-amber-50 px-3 py-2 text-xs leading-relaxed text-amber-800">
                    Menghapus langkah akan menghapus catatan penyelesaiannya di semua penerima.
                </p>

                <div class="space-y-3">
                    <template x-for="(subtask, index) in subtasks" :key="index">
                        <div class="rounded-2xl border border-slate-200 bg-white p-3 shadow-sm sm:p-4">
                            <input type="hidden" :name="`subtasks[${index}][id]`" x-model="subtask.id">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-violet-100 text-xs font-bold text-violet-700" x-text="index + 1"></span>
                                    <span class="text-sm font-semibold text-slate-800">Langkah <span x-text="index + 1"></span></span>
                                </div>
                                <button
                                    type="button"
                                    @click="subtasks.splice(index, 1)"
                                    class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 hover:text-rose-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-500"
                                    :aria-label="`Hapus langkah ${index + 1}`"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12m-10 0 .7 12h6.6L16 7m-6 0V4h4v3m-3 3v6m3-6v6" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                            <label class="mb-1.5 block text-xs font-semibold text-slate-600" :for="`subtask-title-${index}`">Judul langkah</label>
                            <input
                                type="text"
                                :id="`subtask-title-${index}`"
                                :name="`subtasks[${index}][title]`"
                                x-model="subtask.title"
                                placeholder="Contoh: Membuat rancangan database"
                                required
                                class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 hover:border-violet-300 focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100"
                            >
                            <label class="mb-1.5 mt-3 block text-xs font-semibold text-slate-600" :for="`subtask-description-${index}`">Petunjuk <span class="font-normal text-slate-400">(opsional)</span></label>
                            <textarea
                                :id="`subtask-description-${index}`"
                                :name="`subtasks[${index}][description]`"
                                x-model="subtask.description"
                                rows="2"
                                placeholder="Tambahkan penjelasan singkat untuk langkah ini..."
                                class="block w-full resize-y rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 hover:border-violet-300 focus:border-violet-500 focus:outline-none focus:ring-4 focus:ring-violet-100"
                            ></textarea>
                        </div>
                    </template>

                    <div x-show="subtasks.length === 0" class="rounded-xl border border-dashed border-slate-300 bg-white/70 px-4 py-5 text-center">
                        <p class="text-sm font-medium text-slate-600">Belum ada langkah checklist</p>
                        <p class="mt-1 text-xs text-slate-400">Klik tombol di bawah untuk menambahkan subtask.</p>
                    </div>

                    <button
                        type="button"
                        @click="subtasks.push({ id: null, title: '', description: '' })"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-dashed border-violet-300 bg-white px-4 py-3 text-sm font-semibold text-violet-700 shadow-sm transition hover:border-violet-400 hover:bg-violet-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-violet-500 sm:w-auto"
                    >
                        <span class="flex h-5 w-5 items-center justify-center rounded-md bg-violet-100 text-base leading-none">+</span>
                        Tambah Subtask
                    </button>
                </div>
                <x-input-error :messages="$errors->get('subtasks.*.title')" />
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('guru.tasks.index') }}" class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <x-primary-button>Simpan Perubahan</x-primary-button>
            </div>
        </form>
        </div>
    </div>
</x-layouts.app>
