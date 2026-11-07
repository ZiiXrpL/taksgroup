<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'btn-animate inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white
        shadow-md shadow-rose-200 hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-200
        disabled:cursor-not-allowed disabled:opacity-60',
]) }}>
    {{ $slot }}
</button>
