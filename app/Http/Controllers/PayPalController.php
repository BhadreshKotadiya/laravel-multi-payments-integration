<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    public function processPaypalCheckout(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $cart = session()->get('cart');
        $total = 0;

        foreach ($cart as $item) {
            $price = $item['price'];
            $discountedPrice = $price * 0.8;

            $total += $discountedPrice * $item['quantity'];
        }

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $total
                    ]
                ]
            ]
        ]);
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {

                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()
                ->route('checkout')
                ->with('error', 'Something went wrong. Approval link not found.');
        } else {
            return redirect()
                ->route('checkout')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function successTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            Session::forget('cart');

            return redirect()->route('product.index')->with('success', 'Transaction complete.');
        } else {
            return redirect()->route('checkout')->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function cancelTransaction(Request $request)
    {
        return redirect()
            ->route('checkout')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
}
