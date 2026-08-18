<x-layouts.guest :title="__('Welcome')">
    <section class="relative overflow-hidden px-6 py-20 sm:py-28 lg:px-8">
        <div class="absolute inset-x-0 top-0 -z-10 flex justify-center overflow-hidden" aria-hidden="true">
            <div class="h-80 w-80 rounded-full bg-indigo-200/50 blur-3xl dark:bg-indigo-900/30"></div>
        </div>

        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700 dark:border-indigo-800 dark:bg-indigo-950 dark:text-indigo-300">
                Laravel {{ app()->version() }} · Livewire {{ \Composer\InstalledVersions::getPrettyVersion('livewire/livewire') }}
            </span>

            <h1 class="mt-8 text-balance text-4xl font-semibold tracking-tight text-slate-950 sm:text-6xl dark:text-white">
                {{ __('A production-ready Laravel foundation.') }}
            </h1>

            <p class="mx-auto mt-6 max-w-2xl text-pretty text-lg leading-8 text-slate-600 dark:text-slate-300">
                {{ __('A clean starting point for community applications, admin panels, APIs, and internal tools—built around Laravel conventions and reusable business actions.') }}
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a
                    href="https://laravel.com/docs"
                    class="rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    target="_blank"
                    rel="noreferrer"
                >
                    {{ __('Laravel documentation') }}
                </a>

                <a
                    href="https://livewire.laravel.com/docs"
                    class="rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800"
                    target="_blank"
                    rel="noreferrer"
                >
                    {{ __('Explore Livewire') }}
                </a>
            </div>
        </div>
    </section>

    <section class="border-y border-slate-200 bg-white/70 px-6 py-16 dark:border-slate-800 dark:bg-slate-900/50">
        <div class="mx-auto grid max-w-6xl gap-6 md:grid-cols-3 lg:px-8">
            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">01</p>
                <h2 class="mt-3 text-lg font-semibold text-slate-950 dark:text-white">{{ __('Laravel-native') }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('Framework features first, with new abstractions introduced only when they solve real complexity.') }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">02</p>
                <h2 class="mt-3 text-lg font-semibold text-slate-950 dark:text-white">{{ __('Multiple entry points') }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('Livewire, APIs, jobs, and commands can share the same focused application actions.') }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">03</p>
                <h2 class="mt-3 text-lg font-semibold text-slate-950 dark:text-white">{{ __('Quality built in') }}</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">{{ __('Pest, Pint, Larastan, secure defaults, and an incremental architecture keep changes dependable.') }}</p>
            </article>
        </div>
    </section>
</x-layouts.guest>
