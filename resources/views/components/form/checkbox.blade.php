@props(['invalid' => false])

<input
    type="checkbox"
    @if ($invalid) aria-invalid="true" @endif
    {{ $attributes->class([
        'size-4 rounded border-slate-300 bg-white text-indigo-600 shadow-sm focus:ring-2 focus:ring-indigo-500/30 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:ring-offset-slate-950',
        'border-red-500' => $invalid,
    ]) }}
>
