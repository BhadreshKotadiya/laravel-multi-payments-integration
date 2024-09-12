@extends('layouts.master')

@section('title', 'pizza')

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
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-3 mb-3">
                    <div class="card product-card shadow-sm">
                        <img src="{{ asset('pizza/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="description">{{ $product->description }}</p>
                            <p class="card-text text-muted"><strong>Price:</strong> ₹{{ $product->price }}</p>
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
