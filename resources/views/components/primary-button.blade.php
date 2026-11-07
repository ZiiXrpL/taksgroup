<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'btn-animate inline-flex min-h-11 items-center justify-center gap-2 rounded-xl
        bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-2.5 text-sm font-semibold text-white
        shadow-md shadow-indigo-200 hover:from-indigo-700 hover:to-violet-700 hover:shadow-lg
        focus:outline-none focus:ring-4 focus:ring-indigo-200 disabled:cursor-not-allowed disabled:opacity-60',
]) }}>
    {{ $slot }}
</button>
