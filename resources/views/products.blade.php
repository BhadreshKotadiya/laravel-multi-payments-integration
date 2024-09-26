@php
    $isSubscribed = Auth::check() ? auth()->user()->subscribed('prod_Qs2Sz2RupWFjAS') : false;
@endphp

@extends('layouts.master')

@section('title', 'Pizza List')

@section('content')
    <div class="container">
        <h3 class="mb-5 mt-5 font-weight-bold text-center">Delicious Veggie Pizzas to Satisfy Your Cravings!</h3>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if ($isSubscribed)
            <div class="alert alert-warning text-center">
                <button type="button" class="close cursor-pointer" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4>Welcome back, Premium Subscriber! Enjoy exclusive offers!</h4>
                <p>As a premium user, you get <strong>extra discounts</strong> and access to exclusive pizzas!</p>
            </div>
        @else
            <div class="alert alert-danger text-center">
                <button type="button" class="close cursor-pointer" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4>Welcome to Pizza Paradise!</h4>
                <Subscribe>Subscribe to our premium plan to unlock exclusive offers!
                    <strong>20% off</strong> on your first order! <a href="{{ route('subscriptions.index') }}">Subscribe
                        Now</a></p>
            </div>
        @endif

        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-3 mb-3">
                    <div class="card product-card shadow-sm {{ $isSubscribed ? 'premium-user' : '' }}">
                        <a href="{{ route('product.show', ['id' => $product->id]) }}"
                            style="text-decoration: none; color: inherit;">
                            <img src="{{ asset('pizza/' . $product->image) }}" class="card-img-top"
                                alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="description">{{ $product->description }}</p>
                                <p class="card-text text-muted"><strong>Price:</strong> ₹{{ $product->price }}</p>

                                @if ($isSubscribed)
                                    @php
                                        // Apply additional discount for premium users (e.g., 20% off)
                                        $premiumDiscountedPrice = $product->price * 0.8;
                                    @endphp
                                    <p class="text-success">
                                        <strong>Premium Price:</strong> ₹{{ number_format($premiumDiscountedPrice, 2) }}
                                        <small class="text-muted">(20% off!)</small>
                                    </p>
                                @else
                                    <p class="text-primary">
                                    </p>
                                @endif

                                <div class="d-flex justify-content-between">
                                    <!-- Add to Cart Button -->
                                    <button class="btn btn-primary btn-custom-product add-to-cart"
                                        data-product-id="{{ $product->id }}">
                                        Add to Cart
                                    </button>

                                    <!-- Buy Now Button -->
                                    <a href="{{ route('checkout.addSingle', ['id' => $product->id]) }}"
                                        class="btn btn-success btn-custom-product">
                                        Buy Now
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Include jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.add-to-cart').on('click', function() {
                var productId = $(this).data('product-id');

                $.ajax({
                    url: '/cart/add/' + productId,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message, 'Success');
                            var cartCount = parseInt($('#cart-count').text()) || 0;
                            $('#cart-count').text(cartCount + 1);
                        }
                    },
                    error: function() {
                        toastr.error('Failed to add product to cart', 'Error');
                    }
                });
            });
        });
    </script>
@endsection
