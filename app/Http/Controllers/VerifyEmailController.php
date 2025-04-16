<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (! hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            // Email đã xác minh, redirect về FE
            return redirect(config('app.frontend_url') . '/vertify-success?verified=200');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Email vừa xác minh thành công, redirect về FE
        return redirect(config('app.frontend_url') . '/vertify-success?verified=200');
    }
}
