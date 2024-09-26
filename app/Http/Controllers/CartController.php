<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{

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

    public function showCart()
    {
        $cart = session()->get('cart', []);

        $cartData = [];
        foreach ($cart as $id => $details) {
            $price = $details['price'];
            $discountedPrice = $price * 0.8; // 20% discount for premium users
            $cartData[$id] = [
                'image' => $details['image'],
                'name' => $details['name'],
                'quantity' => $details['quantity'],
                'normal_price' => $price,
                'discount_price' => $discountedPrice,
                'is_premium' => is_premium_subscriber(),
            ];
        }

        return view('cart', ['cart' => $cartData]);
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
    public function update(Request $request)
    {
        $cart = session()->get('cart');

        if (isset($cart[$request->id])) {
            // Update the quantity
            $cart[$request->id]['quantity'] = $request->quantity;

            // Calculate the price based on normal price or discount price
            $price = is_premium_subscriber() ? $cart[$request->id]['price'] * 0.8 : $cart[$request->id]['price'];

            // Calculate the total price for the item
            $itemTotal = $price * $request->quantity;

            // Update the session cart
            session()->put('cart', $cart);

            // Calculate the cart total
            $cartTotal = array_reduce($cart, function ($carry, $item) {
                $itemPrice = is_premium_subscriber() ? $item['price'] : $item['price'];
                return $carry + ($itemPrice * $item['quantity']);
            }, 0);

            return response()->json([
                'success' => true,
                'itemTotal' => $itemTotal,
                'cartTotal' => $cartTotal
            ]);
        }

        return response()->json(['success' => false]);
    }


}
