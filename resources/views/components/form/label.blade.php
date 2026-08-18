@props([
    'for' => null,
    'required' => false,
])

<label
    @if ($for) for="{{ $for }}" @endif
    {{ $attributes->class(['block text-sm font-medium text-slate-800 dark:text-slate-200']) }}
>
    {{ $slot }}

    @if ($required)
        <span class="text-red-600" aria-hidden="true">*</span>
        <span class="sr-only">{{ __('required') }}</span>
    @endif
</label>
