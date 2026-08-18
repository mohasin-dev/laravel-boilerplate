<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LogoutUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class LogoutController extends Controller
{
    public function __invoke(Request $request, LogoutUser $logoutUser): RedirectResponse
    {
        $logoutUser->execute();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
