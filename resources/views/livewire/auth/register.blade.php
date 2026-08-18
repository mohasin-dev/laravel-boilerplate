<div class="mx-auto w-full max-w-md px-6 py-16 sm:py-24">
    <x-ui.card>
        <x-slot:header>
            <div>
                <h1 class="text-xl font-semibold text-slate-950 dark:text-white">{{ __('Create your account') }}</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('Start with a secure account you can personalize later.') }}</p>
            </div>
        </x-slot:header>

        <form wire:submit="register" class="space-y-5">
            <div class="space-y-2">
                <x-form.label for="name" required>{{ __('Name') }}</x-form.label>
                <x-form.input
                    id="name"
                    wire:model="name"
                    :invalid="$errors->has('name')"
                    aria-describedby="name-error"
                    autocomplete="name"
                    autofocus
                    required
                />
                <x-form.error id="name-error" :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <x-form.label for="email" required>{{ __('Email address') }}</x-form.label>
                <x-form.input
                    id="email"
                    type="email"
                    wire:model="email"
                    :invalid="$errors->has('email')"
                    aria-describedby="email-error"
                    autocomplete="email"
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
                    autocomplete="new-password"
                    required
                />
                <x-form.error id="password-error" :messages="$errors->get('password')" />
            </div>

            <div class="space-y-2">
                <x-form.label for="password_confirmation" required>{{ __('Confirm password') }}</x-form.label>
                <x-form.input
                    id="password_confirmation"
                    type="password"
                    wire:model="password_confirmation"
                    autocomplete="new-password"
                    required
                />
            </div>

            <x-ui.button type="submit" class="w-full" wire:loading.attr="disabled" wire:target="register">
                <span wire:loading.remove wire:target="register">{{ __('Create account') }}</span>
                <span wire:loading wire:target="register">{{ __('Creating account…') }}</span>
            </x-ui.button>
        </form>

        <x-slot:footer>
            <p class="text-center text-sm text-slate-600 dark:text-slate-300">
                {{ __('Already registered?') }}
                <a href="{{ route('login') }}" wire:navigate class="font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                    {{ __('Log in') }}
                </a>
            </p>
        </x-slot:footer>
    </x-ui.card>
</div>
