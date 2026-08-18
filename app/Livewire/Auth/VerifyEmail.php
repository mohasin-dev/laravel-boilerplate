<?php

namespace App\Livewire\Auth;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Verify email')]
final class VerifyEmail extends Component
{
    public bool $verificationLinkSent = false;

    public function sendVerificationEmail(): void
    {
        $user = auth()->user();

        if (! $user instanceof MustVerifyEmail) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            $this->redirectRoute('home', navigate: true);

            return;
        }

        $throttleKey = 'verification-email:'.$user->getAuthIdentifier();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            $this->addError('verification', __('Please wait :seconds seconds before requesting another verification email.', [
                'seconds' => $seconds,
            ]));

            return;
        }

        $user->sendEmailVerificationNotification();
        RateLimiter::hit($throttleKey, 60);

        $this->verificationLinkSent = true;
    }

    public function render(): View
    {
        return view('livewire.auth.verify-email');
    }
}
