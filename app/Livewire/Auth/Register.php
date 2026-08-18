<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\RegisterUser;
use App\DTOs\Auth\RegisterUserData;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Create account')]
final class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function register(RegisterUser $registerUser): void
    {
        $validated = $this->validate();

        $user = $registerUser->execute(new RegisterUserData(
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
        ));

        Auth::guard('web')->login($user);
        session()->regenerate();

        $this->redirectRoute('verification.notice', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.register');
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ];
    }
}
