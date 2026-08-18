@props([
    'title' => null,
    'description' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @include('partials.head', ['title' => $title, 'description' => $description])
    </head>
    <body class="h-full bg-slate-50 font-sans text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        <div class="min-h-full" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
            <a href="#main-content" class="sr-only z-50 rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-950 focus:not-sr-only focus:fixed focus:left-4 focus:top-4 dark:bg-slate-800 dark:text-white">
                {{ __('Skip to content') }}
            </a>

            <div
                x-cloak
                x-show="sidebarOpen"
                class="fixed inset-0 z-40 bg-slate-950/60 lg:hidden"
                @click="sidebarOpen = false"
                aria-hidden="true"
            ></div>

            <aside
                class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform lg:w-64 lg:translate-x-0 dark:border-slate-800 dark:bg-slate-900"
                :class="sidebarOpen && 'translate-x-0'"
                aria-label="{{ __('Application navigation') }}"
            >
                <div class="flex h-16 items-center justify-between border-b border-slate-200 px-5 dark:border-slate-800">
                    <a href="{{ route('home') }}" wire:navigate>
                        <x-application-logo />
                    </a>

                    <button
                        type="button"
                        class="rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 lg:hidden dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        @click="sidebarOpen = false"
                        aria-label="{{ __('Close navigation') }}"
                    >
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
                        </svg>
                    </button>
                </div>

                <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                    @isset($navigation)
                        {{ $navigation }}
                    @else
                        <a
                            href="{{ route('home') }}"
                            wire:navigate
                            @class([
                                'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition',
                                'bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300' => request()->routeIs('home'),
                                'text-slate-600 hover:bg-slate-100 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' => ! request()->routeIs('home'),
                            ])
                            @if (request()->routeIs('home')) aria-current="page" @endif
                        >
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5V21a.75.75 0 0 1-.75.75h-5.5v-6.5h-5.5v6.5h-5.5A.75.75 0 0 1 3 21V10.5Z" />
                            </svg>
                            {{ __('Home') }}
                        </a>
                    @endisset
                </nav>

                <div class="border-t border-slate-200 p-4 text-xs leading-5 text-slate-500 dark:border-slate-800 dark:text-slate-400">
                    {{ __('Navigation grows as modules are enabled.') }}
                </div>
            </aside>

            <div class="lg:pl-64">
                <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-slate-200 bg-white/90 px-4 backdrop-blur sm:px-6 lg:px-8 dark:border-slate-800 dark:bg-slate-950/90">
                    <button
                        type="button"
                        class="rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 lg:hidden dark:hover:bg-slate-800 dark:hover:text-slate-200"
                        @click="sidebarOpen = true"
                        aria-label="{{ __('Open navigation') }}"
                    >
                        <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>

                    <div class="min-w-0 flex-1">
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="truncate text-lg font-semibold text-slate-950 dark:text-white">{{ $title ?? config('app.name') }}</h1>
                        @endisset
                    </div>

                    @isset($actions)
                        <div class="flex items-center gap-2">{{ $actions }}</div>
                    @endisset
                </header>

                <main id="main-content" class="p-4 sm:p-6 lg:p-8">
                    <div class="mx-auto max-w-7xl">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
