<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        // Check if user is already verified
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/')->with('message', 'Email ya verificado');
        }

        // Verify the email
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Ensure user is logged in
        if (!Auth::check()) {
            Auth::login($request->user());
        }

        return redirect('/')->with('success', 'Email verificado exitosamente. Ahora puedes agregar productos al carrito.');
    }
}
