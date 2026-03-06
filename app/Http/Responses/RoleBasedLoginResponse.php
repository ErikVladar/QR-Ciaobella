<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class RoleBasedLoginResponse implements LoginResponseContract, TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request)
    {
        /** @var Request $request */
        $user = $request->user();

        if ($user && method_exists($user, 'isKitchen') && $user->isKitchen()) {
            return redirect()->route('kitchen.index');
        }

        if ($user && method_exists($user, 'isWaiter') && $user->isWaiter()) {
            return redirect()->route('waiter.index');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
