<div class="mx-auto w-full max-w-lg px-6 py-16 sm:py-24">
    <x-ui.card>
        <x-slot:header>
            <div>
                <h1 class="text-xl font-semibold text-slate-950 dark:text-white">{{ __('Verify your email address') }}</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ __('One quick step keeps your account secure.') }}</p>
            </div>
        </x-slot:header>

        <div class="space-y-5">
            <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('We sent a verification link to :email. Open that link to finish verifying your account.', ['email' => auth()->user()?->email]) }}
            </p>

            @if ($verificationLinkSent)
                <x-ui.alert variant="success">
                    {{ __('A new verification link has been sent to your email address.') }}
                </x-ui.alert>
            @endif

            <x-form.error :messages="$errors->get('verification')" />

            <x-ui.button wire:click="sendVerificationEmail" wire:loading.attr="disabled" wire:target="sendVerificationEmail">
                <span wire:loading.remove wire:target="sendVerificationEmail">{{ __('Resend verification email') }}</span>
                <span wire:loading wire:target="sendVerificationEmail">{{ __('Sending…') }}</span>
            </x-ui.button>
        </div>

        <x-slot:footer>
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                    {{ __('Log out') }}
                </button>
            </form>
        </x-slot:footer>
    </x-ui.card>
</div>
