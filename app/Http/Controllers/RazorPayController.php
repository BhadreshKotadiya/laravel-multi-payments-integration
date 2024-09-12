<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;

class RazorPayController extends Controller
{
    public function processRazorPayCheckout(Request $request)
    {
        // TODO: Register in this site for keys https://x.razorpay.com/ledger

        $input = $request->all();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            // Fetch the payment using the payment ID
            $payment = $api->payment->fetch($input['razorpay_payment_id']);

            // Check if the payment ID is valid and not empty
            if (!empty($input['razorpay_payment_id'])) {
                // Capture the payment
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture([
                    'amount' => $payment['amount']
                ]);

                Session::forget('cart');
                return redirect()->route('product.index')->with('success', 'Transaction complete.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
