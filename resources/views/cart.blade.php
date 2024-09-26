@extends('layouts.master')

@section('title', 'Cart')

@section('content')
    <h3 class="mb-5 mt-5 font-weight-bold text-center">Your Cart</h3>
    <div class="card">
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            @if (!empty($cart))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Items</th>
                            <th style="width:200px;" class="text-center">Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp

                        @foreach ($cart as $id => $details)
                            @php
                                $price = $details['is_premium'] ? $details['discount_price'] : $details['normal_price'];
                                $itemTotal = $price * $details['quantity'];
                                $total += $itemTotal;
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ asset('pizza/' . $details['image']) }}" alt="" class="img-thumbnail"
                                        style="max-width: 100px;">
                                </td>
                                <td>{{ $details['name'] }}</td>
                                <td>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary btn-minus m-1"
                                            data-id="{{ $id }}" {{ $details['quantity'] == 1 ? 'disabled' : '' }}>
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <input type="text" class="form-control text-center quantity-input m-1"
                                            value="{{ $details['quantity'] }}" data-id="{{ $id }}" readonly>
                                        <button class="btn btn-outline-secondary btn-plus m-1"
                                            data-id="{{ $id }}">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </td>


                                <td>₹{{ number_format($price, 2) }}</td>
                                <td class="item-total" data-id="{{ $id }}">₹{{ number_format($itemTotal, 2) }}
                                </td>
                                <td>
                                    <a href="{{ route('cart.remove', $id) }}" class="btn btn-danger">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
                            <td id="cart-subtotal">₹{{ number_format($total, 2) }}</td>
                            <td></td>
                        </tr>

                    </tbody>
                </table>
                <div class="text-center mt-4">
                    <a href="{{ route('checkout') }}" class="btn btn-success btn-lg">Proceed to Checkout</a>
                </div>
            @else
                <div class="text-center">
                    <p>No items in cart</p>
                    <a href="{{ route('product.index') }}" class="btn btn-primary">Browse Products</a>
                </div>
            @endif
        </div>
    </div>
@endsection
