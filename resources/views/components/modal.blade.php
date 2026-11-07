{{--
    Modal Alpine yang dapat dipakai ulang.
    Pemakaian:
      <div x-data="{ open: false }">
          <button @click="open = true">Buka</button>
          <x-modal title="Judul">isi...</x-modal>
      </div>
    Variabel `open` berasal dari x-data induk.
--}}
@props(['title' => null, 'maxWidth' => 'max-w-lg'])

<div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-end justify-center bg-slate-950/60 p-0 backdrop-blur-sm sm:items-center sm:p-4" @keydown.escape.window="open = false" role="dialog" aria-modal="true">
    <div @click.outside="open = false" x-transition class="max-h-[90vh] w-full {{ $maxWidth }} overflow-y-auto rounded-t-3xl bg-white p-5 shadow-2xl sm:rounded-3xl sm:p-6">
        <div class="mb-4 flex items-start justify-between gap-3">
            @if ($title)
                <h3 class="text-lg font-bold tracking-tight text-slate-900">{{ $title }}</h3>
            @endif
            <button type="button" @click="open = false" class="ml-auto rounded-lg p-1.5 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
        {{ $slot }}
    </div>
</div>
