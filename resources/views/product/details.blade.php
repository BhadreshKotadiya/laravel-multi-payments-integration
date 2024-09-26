@extends('layouts.master')

@section('title', 'Product Details')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <!-- Large Product Image -->
                <div class="product-image">
                    <img src="{{ asset('pizza/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="product-title">{{ $product->name }}</h1>
                <p class="product-description">{{ $product->description }}</p>
                <p class="product-price">
                    <strong>Price:</strong> ₹{{ $product->price }}
                </p>

                @if ($isSubscribed)
                    @php
                        // Apply additional discount for premium users (e.g., 20% off)
                        $premiumDiscountedPrice = $product->price * 0.8;
                    @endphp
                    <p class="product-premium-price">
                        <strong>Premium Price:</strong> ₹{{ number_format($premiumDiscountedPrice, 2) }}
                        <small class="text-muted">(20% off!)</small>
                    </p>
                @else
                    @php
                        $regularDiscountedPrice = $product->price;
                    @endphp
                    <p class="product-discounted-price">
                        <strong>Discounted Price:</strong> ₹{{ number_format($regularDiscountedPrice, 2) }}
                    </p>
                @endif

                <div class="mt-4">
                    <!-- Add to Cart Button -->
                    <button class="btn btn-primary btn-lg btn-custom" data-product-id="{{ $product->id }}">
                        Add to Cart
                    </button>

                    <!-- Buy Now Button -->
                    <a href="{{ route('checkout.addSingle', ['id' => $product->id]) }}"
                        class="btn btn-success btn-lg btn-custom">
                        Buy Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-custom').on('click', function() {
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
