@props(['class' => ''])

{{--
    Placeholder skeleton dengan efek shimmer. Dipakai untuk status loading.
    Contoh:
        <x-skeleton class="h-4 w-32" />
        <x-skeleton class="skeleton-circle h-10 w-10" />
        <x-skeleton class="h-24 w-full" />
--}}

<div {{ $attributes->merge(['class' => 'skeleton '.$class]) }} aria-hidden="true"></div>
