@props(['disabled' => false])

<select @disabled($disabled) {{ $attributes->merge([
    'class' => 'block w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 shadow-sm transition hover:border-blue-300 hover:bg-white focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100',
]) }}>
    {{ $slot }}
</select>
