<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class LoginUser
{
    public function execute(LoginData $data): bool
    {
        return Auth::guard('web')->attempt([
            'email' => Str::lower(trim($data->email)),
            'password' => $data->password,
            'is_active' => true,
        ], $data->remember);
    }
}
