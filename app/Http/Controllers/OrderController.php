<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public $phonePeController;
    public $paypal;
    public $RazorPayController;
    public $CardPaymentController;
    public $GooglePayController;
    public function __construct(
        PhonePeController $phonePeController,
        PayPalController $payPalController,
        RazorPayController $RazorPayController,
        CardPaymentController $CardPaymentController,
        GooglePayController $GooglePayController
    ) {
        $this->phonePeController = $phonePeController;
        $this->paypal = $payPalController;
        $this->RazorPayController = $RazorPayController;
        $this->CardPaymentController = $CardPaymentController;
        $this->GooglePayController = $GooglePayController;
    }

    public function addToCart($id)
    {
        $product = Product::find($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Pizza added to cart!'
        ]);
    }

    public function showCart(Request $request)
    {
        $cart = session()->get('cart');
        return view('cart', compact('cart'));
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart');
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart') ?? [];
        $product_id = $request->query('product_id');

        $product = $product_id ? Product::find($product_id) : null;

        return view('checkout', compact('cart', 'product'));
    }

    public function addSingleProductToCheckout($id)
    {
        $cart = session()->get('cart', []);
        $product = Product::find($id);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
        } elseif ($product) {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
            session()->put('cart', $cart);
        }
        return redirect()->route('checkout');
    }

    public function processCheckout(Request $request)
    {
        // Validate the request to ensure a payment method is selected
        $request->validate([
            'payment_method' => 'required'
        ]);

        switch ($request->payment_method) {
            case 'credit_card':
                return $this->CardPaymentController->processCreditCardCheckout($request);
            case 'paypal':
                return $this->paypal->processPaypalCheckout($request);
            case 'google_pay':
                return $this->GooglePayController->processGooglePayCheckout($request);
            case 'phone_pay':
                return $this->phonePeController->processPhonePayCheckout($request);
            case 'razor_pay':

                if ($request->has('razorpay_payment_id')) {
                    return $this->RazorPayController->processRazorPayCheckout($request);
                } else {
                    return redirect()->back()->with('error', 'Razorpay payment failed or was not completed.');
                }
            case 'paytm':
                return view('coming-soon');
            default:
                return redirect()->back()->with('error', 'Invalid payment method.');
        }
    }
}
