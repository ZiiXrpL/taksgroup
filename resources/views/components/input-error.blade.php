@props(['messages'])

@if ($messages)
    <p {{ $attributes->merge(['class' => 'mt-1 text-sm text-status-danger']) }}>
        @foreach ((array) $messages as $message)
            {{ $message }}
        @endforeach
    </p>
@endif
