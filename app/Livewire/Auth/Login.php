<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\LoginUser;
use App\DTOs\Auth\LoginData;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Log in')]
final class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function authenticate(LoginUser $loginUser): void
    {
        $this->validate();

        $throttleKey = $this->throttleKey();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            $this->addError('email', __('Too many login attempts. Please try again in :seconds seconds.', [
                'seconds' => $seconds,
            ]));

            return;
        }

        $authenticated = $loginUser->execute(new LoginData(
            email: Str::lower(trim($this->email)),
            password: $this->password,
            remember: $this->remember,
        ));

        if (! $authenticated) {
            RateLimiter::hit($throttleKey, 60);

            $this->reset('password');
            $this->addError('email', __('The provided credentials do not match our records.'));

            return;
        }

        RateLimiter::clear($throttleKey);
        session()->regenerate();

        $this->redirectIntended(route('home'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }

    /**
     * @return array<string, list<string>>
     */
    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower(trim($this->email))).'|'.request()->ip();
    }
}
