@props(['messages' => []])

@php
    $messages = array_filter((array) $messages);
@endphp

@if ($messages)
    <ul {{ $attributes->class(['space-y-1 text-sm text-red-600 dark:text-red-400']) }}>
        @foreach ($messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
