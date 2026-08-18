<div class="mx-auto w-full max-w-md px-6 py-16 sm:py-24">
    <x-ui.card>
        <x-slot:header>
            <div>
                <h1 class="text-xl font-semibold text-slate-950 dark:text-white">{{ __('Welcome back') }}</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('Sign in to continue to your account.') }}</p>
            </div>
        </x-slot:header>

        <form wire:submit="authenticate" class="space-y-5">
            <div class="space-y-2">
                <x-form.label for="email" required>{{ __('Email address') }}</x-form.label>
                <x-form.input
                    id="email"
                    type="email"
                    wire:model="email"
                    :invalid="$errors->has('email')"
                    aria-describedby="email-error"
                    autocomplete="email"
                    autofocus
                    required
                />
                <x-form.error id="email-error" :messages="$errors->get('email')" />
            </div>

            <div class="space-y-2">
                <x-form.label for="password" required>{{ __('Password') }}</x-form.label>
                <x-form.input
                    id="password"
                    type="password"
                    wire:model="password"
                    :invalid="$errors->has('password')"
                    aria-describedby="password-error"
                    autocomplete="current-password"
                    required
                />
                <x-form.error id="password-error" :messages="$errors->get('password')" />
            </div>

            <label class="flex items-center gap-3 text-sm text-slate-700 dark:text-slate-300">
                <x-form.checkbox wire:model="remember" />
                <span>{{ __('Remember me') }}</span>
            </label>

            <x-ui.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="authenticate">
                <span wire:loading.remove wire:target="authenticate">{{ __('Log in') }}</span>
                <span wire:loading wire:target="authenticate">{{ __('Signing in…') }}</span>
            </x-ui.button>
        </form>
    </x-ui.card>
</div>
