@props(['align' => 'right', 'width' => 'w-56'])

<div x-data="{ open: false }" class="relative inline-block text-left">
    <div @click="open = ! open" @click.outside="open = false">
        {{ $trigger }}
    </div>

    <div x-cloak x-show="open" x-transition @click="open = false"
        class="absolute z-40 mt-2 {{ $width }} {{ $align === 'left' ? 'left-0' : 'right-0' }} overflow-hidden rounded-2xl border border-slate-200 bg-white p-1.5 shadow-xl">
        {{ $content }}
    </div>
</div>
