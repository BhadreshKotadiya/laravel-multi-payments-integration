<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function checkout(Request $request)
    {
        $cart = session()->get('cart') ?? [];
        $addresses = [];
        $selectedAddressId = null;

        if (Auth::check()) {
            // Fetch addresses with the latest one marked as selected by default
            $addresses = Auth::user()->addresses->toArray();
            $latestAddress = end($addresses); // Get the latest address
            $selectedAddressId = $latestAddress['id'];

            // Mark the latest address as selected
            foreach ($addresses as &$address) {
                $address['selected_address'] = $address['id'] == $selectedAddressId;
            }
        }

        // Update cart items with pricing information
        $cartData = [];
        foreach ($cart as $id => $details) {
            $price = $details['price'];
            $discountedPrice = $price * 0.8; // Example: 20% discount
            $cartData[$id] = [
                'image' => $details['image'],
                'name' => $details['name'],
                'quantity' => $details['quantity'],
                'normal_price' => $price,
                'discount_price' => $discountedPrice,
                'is_premium' => is_premium_subscriber(),
            ];
        }

        $product_id = $request->query('product_id');
        $product = $product_id ? Product::find($product_id) : null;

        return view('checkout', compact('cartData', 'product', 'addresses', 'selectedAddressId'));
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

    public function updateSelectedAddress(Request $request)
    {
        $user = Auth::user();
        $addressId = $request->input('address_id');

        // Reset all addresses to not selected
        foreach ($user->addresses as $address) {
            $address->update(['selected_address' => false]);
        }

        // Mark the selected address as true
        $selectedAddress = $user->addresses()->where('id', $addressId)->first();
        $selectedAddress->update(['selected_address' => true]);

        return response()->json($selectedAddress);
    }

    public function processCheckout(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'payment_method' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
            'sameadr' => 'nullable|boolean',
        ]);

        if (!$validatedData) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }

        $request['user_address_id'] = $this->storeUserAddress($request);

        switch ($request->payment_method) {
            case 'credit_card':
                return $this->CardPaymentController->processCreditCardCheckout($request);
            case 'paypal':
                return $this->paypal->processPaypalCheckout($request);
            case 'google_pay':
                return view('coming-soon');
            // return $this->GooglePayController->processGooglePayCheckout($request);
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

    public function storeUserAddress($request)
    {
        $user = User::findOrFail(Auth::id());

        if ($request->has('address_id')) {
            $orderAddress = UserAddress::where('id', $request->address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            // Update the existing address with the new data
            $orderAddress->update([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'sameadr' => $request->sameadr ?? 0
            ]);

        } else {
            // If no address_id is provided, create a new address
            $orderAddress = UserAddress::create([
                'name' => $request->name,
                'email' => $request->email,
                'user_id' => $user->id,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'sameadr' => $request->sameadr ?? 0
            ]);
        }

        return $orderAddress->id;
    }

}
