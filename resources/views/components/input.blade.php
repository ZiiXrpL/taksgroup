@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
    'class' => 'block w-full min-h-11 rounded-xl border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 hover:border-indigo-300 hover:bg-white focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100 disabled:cursor-not-allowed disabled:bg-slate-100',
]) }}>
