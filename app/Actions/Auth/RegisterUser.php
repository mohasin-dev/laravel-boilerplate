<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\RegisterUserData;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class RegisterUser
{
    public function execute(RegisterUserData $data): User
    {
        $user = User::query()->create([
            'name' => trim($data->name),
            'email' => Str::lower(trim($data->email)),
            'password' => Hash::make($data->password),
            'is_active' => true,
        ]);

        event(new Registered($user));

        return $user;
    }
}
