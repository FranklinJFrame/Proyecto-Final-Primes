<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        // Buscar el usuario por ID ya que puede no estar autenticado
        $user = User::findOrFail($id);
        
        // Verificar que el hash sea válido
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect('/')->with('error', 'Enlace de verificación inválido');
        }
        
        // Si ya está verificado, redirigir
        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('success', 'El correo ya ha sido verificado');
        }
        
        // Marcar como verificado - Actualización directa para asegurar que se establezca
        DB::table('users')
            ->where('id', $user->id)
            ->update(['email_verified_at' => now()]);
        
        // Actualizar el modelo en memoria
        $user->email_verified_at = now();
        $user->save();
        
        // Disparar evento de verificación
        event(new Verified($user));
        
        // Autenticar al usuario si no está autenticado
        if (!Auth::check()) {
            Auth::login($user);
        }
        
        return view('verify-email');
    }

    public function resend(Request $request)
    {
        $user = $request->user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('success', 'El correo ya ha sido verificado');
        }
        
        $user->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
