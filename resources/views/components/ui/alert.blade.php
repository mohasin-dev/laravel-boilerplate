@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => false,
])

@php
    [$classes, $role] = match ($variant) {
        'success' => ['border-emerald-200 bg-emerald-50 text-emerald-900 dark:border-emerald-900 dark:bg-emerald-950 dark:text-emerald-100', 'status'],
        'warning' => ['border-amber-200 bg-amber-50 text-amber-950 dark:border-amber-900 dark:bg-amber-950 dark:text-amber-100', 'alert'],
        'danger' => ['border-red-200 bg-red-50 text-red-900 dark:border-red-900 dark:bg-red-950 dark:text-red-100', 'alert'],
        default => ['border-blue-200 bg-blue-50 text-blue-900 dark:border-blue-900 dark:bg-blue-950 dark:text-blue-100', 'status'],
    };
@endphp

<div
    @if ($dismissible) x-data="{ visible: true }" x-show="visible" x-transition @endif
    role="{{ $role }}"
    {{ $attributes->class(['relative rounded-xl border p-4 text-sm', $classes]) }}
>
    @if ($title)
        <p class="font-semibold">{{ $title }}</p>
    @endif

    <div @class(['leading-6', 'mt-1' => $title])>{{ $slot }}</div>

    @if ($dismissible)
        <button
            type="button"
            class="absolute right-2 top-2 rounded-md p-1 opacity-70 transition hover:opacity-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
            @click="visible = false"
            aria-label="{{ __('Dismiss message') }}"
        >
            <svg class="size-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" d="m5 5 10 10M15 5 5 15" />
            </svg>
        </button>
    @endif
</div>
