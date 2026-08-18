@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
    'disabled' => false,
])

@php
    $classes = implode(' ', [
        'inline-flex items-center justify-center gap-2 rounded-lg font-semibold shadow-sm transition focus-visible:outline-2 focus-visible:outline-offset-2 disabled:pointer-events-none disabled:opacity-50',
        match ($variant) {
            'secondary' => 'border border-slate-300 bg-white text-slate-800 hover:bg-slate-50 focus-visible:outline-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800',
            'danger' => 'bg-red-600 text-white hover:bg-red-500 focus-visible:outline-red-600',
            'ghost' => 'bg-transparent text-slate-700 shadow-none hover:bg-slate-100 focus-visible:outline-slate-500 dark:text-slate-200 dark:hover:bg-slate-800',
            default => 'bg-indigo-600 text-white hover:bg-indigo-500 focus-visible:outline-indigo-600',
        },
        match ($size) {
            'sm' => 'px-3 py-2 text-xs',
            'lg' => 'px-5 py-3 text-base',
            default => 'px-4 py-2.5 text-sm',
        },
    ]);
@endphp

@if ($href)
    <a
        @if (! $disabled) href="{{ $href }}" @endif
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif
        {{ $attributes->class($classes) }}
    >
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" @disabled($disabled) {{ $attributes->class($classes) }}>
        {{ $slot }}
    </button>
@endif
