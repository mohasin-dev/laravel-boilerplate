@props(['invalid' => false])

<select
    @if ($invalid) aria-invalid="true" @endif
    {{ $attributes->class([
        'block w-full rounded-lg border bg-white px-3 py-2.5 text-sm text-slate-950 shadow-sm outline-none transition focus:ring-2 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500 dark:bg-slate-900 dark:text-white dark:disabled:bg-slate-800',
        'border-red-400 focus:border-red-500 focus:ring-red-500/20 dark:border-red-600' => $invalid,
        'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500/20 dark:border-slate-700' => ! $invalid,
    ]) }}
>
    {{ $slot }}
</select>
