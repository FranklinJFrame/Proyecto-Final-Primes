<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request)
    {
        try {
            $user = User::find($request->route('id'));

            if (!$user) {
                Log::error('Email verification failed: User not found', [
                    'user_id' => $request->route('id')
                ]);
                return redirect('/')->with('error', 'Usuario no encontrado.');
            }

            if ($user->hasVerifiedEmail()) {
                return redirect('/')->with('message', 'Email ya verificado');
            }

            // Verify hash matches
            if (!hash_equals(sha1($user->getEmailForVerification()), (string) $request->route('hash'))) {
                Log::error('Email verification failed: Invalid hash', [
                    'user_id' => $user->id,
                    'provided_hash' => $request->route('hash')
                ]);
                return redirect('/')->with('error', 'Link de verificación inválido');
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            // Log the user in if not already logged in
            if (!Auth::check()) {
                Auth::login($user);
            }

            return redirect('/')->with('success', 'Email verificado exitosamente. Ahora puedes agregar productos al carrito.');

        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage(), [
                'user_id' => $request->route('id'),
                'exception' => $e
            ]);
            return redirect('/')->with('error', 'Hubo un error al verificar tu email. Por favor, intenta nuevamente.');
        }
    }
}
