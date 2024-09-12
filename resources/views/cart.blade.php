@extends('layouts.master')

@section('title', 'Cart')

@section('content')
    <h3 class="mb-5 mt-5 font-weight-bold">Your Cart</h3>
    <div class="card">
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            @if (!empty($cart))
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Items</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $id => $details)
                            <tr>
                                <td>
                                    <img src="{{ asset('pizza/' . $details['image']) }}" alt="">
                                </td>
                                <td>{{ $details['name'] }}</td>
                                <td>{{ $details['quantity'] }}</td>
                                <td>₹{{ $details['price'] }}</td>
                                <td>₹{{ $details['price'] * $details['quantity'] }}</td>
                                <td>
                                    <a href="{{ route('cart.remove', $id) }}" class="btn btn-dark">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total:</strong></td>
                            <td>₹{{ array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <a href="{{ route('checkout') }}" class="btn btn-success">Proceed to Checkout</a>
            @else
                <table class="table">
                    <tr>
                        <td colspan="5">No items in cart</td>
                    </tr>
                </table>
            @endif
        </div>
    </div>
@endsection
