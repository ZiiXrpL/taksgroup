<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'btn-animate inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5
        text-sm font-semibold text-slate-700 shadow-sm hover:border-indigo-200 hover:bg-indigo-50/50 hover:text-indigo-700
        focus:outline-none focus:ring-4 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:opacity-60',
]) }}>
    {{ $slot }}
</button>
