<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaMail;

class PaymentController extends Controller
{
    public function stripe(Pedidos $pedido)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        // Verificar que el método de pago es tarjeta
        if ($pedido->metodo_pago !== 'tarjeta') {
            abort(400, 'Método de pago inválido');
        }

        return view('payment.stripe', [
            'pedido' => $pedido,
        ]);
    }

    public function paypal(Pedidos $pedido)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->user_id !== auth()->id()) {
            abort(403);
        }

        // Verificar que el método de pago es paypal
        if ($pedido->metodo_pago !== 'paypal') {
            abort(400, 'Método de pago inválido');
        }

        return view('payment.paypal', [
            'pedido' => $pedido,
        ]);
    }

    public function stripeWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, config('services.stripe.webhook_secret')
            );
        } catch(\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $pedido = Pedidos::where('stripe_payment_intent', $paymentIntent->id)->first();
            
            if ($pedido) {
                $pedido->update([
                    'estado_pago' => 'pagado',
                    'estado' => 'procesando'
                ]);
                // Enviar factura por correo
                $direccionUsuario = $pedido->user && $pedido->user->direccions->count() > 0 ? $pedido->user->direccions->first() : null;
                $subtotal = $pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; });
                $impuestos = round($subtotal * 0.18, 2);
                $envio = $pedido->costo_envio ?? 0;
                $total = $subtotal + $impuestos + $envio;
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.factura', [
                    'pedido' => $pedido,
                    'subtotal' => $subtotal,
                    'impuestos' => $impuestos,
                    'envio' => $envio,
                    'total' => $total,
                    'direccionUsuario' => $direccionUsuario
                ]);
                Mail::to($pedido->user->email)->send(new FacturaMail($pedido, $pdf->output()));
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function paypalWebhook(Request $request)
    {
        // Verificar la autenticidad del webhook
        $payload = $request->getContent();
        $headers = $request->header();

        try {
            // Aquí iría la lógica de verificación del webhook de PayPal
            $data = json_decode($payload, true);
            
            if ($data['event_type'] === 'PAYMENT.CAPTURE.COMPLETED') {
                $pedido = Pedidos::where('paypal_order_id', $data['resource']['id'])->first();
                
                if ($pedido) {
                    $pedido->update([
                        'estado_pago' => 'pagado',
                        'estado' => 'procesando'
                    ]);
                    // Enviar factura por correo
                    $direccionUsuario = $pedido->user && $pedido->user->direccions->count() > 0 ? $pedido->user->direccions->first() : null;
                    $subtotal = $pedido->productos->sum(function($item) { return $item->precio_unitario * $item->cantidad; });
                    $impuestos = round($subtotal * 0.18, 2);
                    $envio = $pedido->costo_envio ?? 0;
                    $total = $subtotal + $impuestos + $envio;
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.factura', [
                        'pedido' => $pedido,
                        'subtotal' => $subtotal,
                        'impuestos' => $impuestos,
                        'envio' => $envio,
                        'total' => $total,
                        'direccionUsuario' => $direccionUsuario
                    ]);
                    Mail::to($pedido->user->email)->send(new FacturaMail($pedido, $pdf->output()));
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
} 