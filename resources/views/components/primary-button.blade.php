<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center gap-2 rounded-xl
        bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white
        shadow-md shadow-blue-200 transition hover:-translate-y-0.5 hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg focus:outline-none
        focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed disabled:opacity-60',
]) }}>
    {{ $slot }}
</button>
