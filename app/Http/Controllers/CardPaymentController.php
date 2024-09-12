<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class CardPaymentController extends Controller
{

    public function processCreditCardCheckout(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $cart = session()->get('cart');
        $user = Auth::user();
        $total = 0;
        $line_items = [];

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $line_items[] = [
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => $item['name'],

                    ],
                    'unit_amount' => $item['price'] * 100, // Convert to cents
                ],
                'quantity' => $item['quantity'],
            ];
        }

        $customer = $stripe->customers->create([
            'name' => $user->name,
            'email' => $user->email,
            'address' => [
                'line1' => '510 Townsend St.',
                'postal_code' => '98140',
                'city' => 'San Francisco',
                'state' => 'CA',
                'country' => 'US',
            ],
        ]);

        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'customer' => $customer->id,
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);
        // . '?session_id={CHECKOUT_SESSION_ID}',

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'status' => 'unpaid',
            'total' => $total,
            'session_id' => $session->id
        ]);

        foreach ($cart as $id => $details) {
            $order->items()->create([
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price']
            ]);
        }
        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        Session::forget('cart');

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        $sessionID = $request->get('session_id');

        $session = $stripe->checkout->sessions->retrieve($sessionID);
        if (!$session) {
            throw new NotFoundHttpException;
        }

        $customer = $stripe->customers->retrieve($session->customer);

        $order = Order::where('session_id', $session->id)->where('status', 'unpaid')->first();
        if (!$order) {
            throw new NotFoundHttpException;
        }
        $order->status = 'paid';
        $order->save();

        dd('success');


    }
    public function cancel()
    {
        dd('failed');
    }

    public function webhook(Request $request)
    {
        Log::info('Webhook received: ' . $request->getContent());
        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}
