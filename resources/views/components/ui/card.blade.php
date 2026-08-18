@props(['padding' => true])

<section {{ $attributes->class(['overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900']) }}>
    @isset($header)
        <header class="border-b border-slate-200 px-5 py-4 sm:px-6 dark:border-slate-800">
            {{ $header }}
        </header>
    @endisset

    <div @class(['p-5 sm:p-6' => $padding])>
        {{ $slot }}
    </div>

    @isset($footer)
        <footer class="border-t border-slate-200 bg-slate-50 px-5 py-4 sm:px-6 dark:border-slate-800 dark:bg-slate-900/50">
            {{ $footer }}
        </footer>
    @endisset
</section>
