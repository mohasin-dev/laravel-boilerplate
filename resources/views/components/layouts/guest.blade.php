@props([
    'title' => null,
    'description' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
    <head>
        @include('partials.head', ['title' => $title, 'description' => $description])
    </head>
    <body class="min-h-full bg-slate-50 font-sans text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        <a href="#main-content" class="sr-only z-50 rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-950 focus:not-sr-only focus:fixed focus:left-4 focus:top-4 dark:bg-slate-800 dark:text-white">
            {{ __('Skip to content') }}
        </a>

        <header class="border-b border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80">
            <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6 lg:px-8" aria-label="{{ __('Primary navigation') }}">
                <a href="{{ route('home') }}" wire:navigate aria-label="{{ __('Home') }}">
                    <x-application-logo />
                </a>

                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                    {{ __('Community boilerplate') }}
                </span>
            </nav>
        </header>

        <main id="main-content">
            {{ $slot }}
        </main>

        <footer class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
            &copy; {{ now()->year }} {{ config('app.name') }}. {{ __('Built with Laravel and Livewire.') }}
        </footer>

        @livewireScripts
    </body>
</html>
