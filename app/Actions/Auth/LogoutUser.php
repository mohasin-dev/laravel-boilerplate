<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

final class LogoutUser
{
    public function execute(): void
    {
        Auth::guard('web')->logout();
    }
}
