@props(['compact' => false])

<span {{ $attributes->class(['inline-flex items-center gap-3']) }}>
    <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-sm font-bold text-white shadow-sm" aria-hidden="true">LB</span>

    @unless ($compact)
        <span class="font-semibold tracking-tight text-slate-950 dark:text-white">{{ config('app.name') }}</span>
    @endunless
</span>
