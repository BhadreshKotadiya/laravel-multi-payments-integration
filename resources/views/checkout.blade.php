@extends('layouts.master')

@section('title', 'Checkout')

@section('content')
    <h3 class="mb-5 mt-5 font-weight-bold">Checkout</h3>
    <div class="card">
        <div class="card-body">
            @include('partials.alert')
            <h5 class="card-title">Order Summary</h5>
            @if ($cart)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Items</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cart as $id => $details)
                            <tr>
                                <td>
                                    <img src="{{ asset('pizza/' . $details['image']) }}" alt="">
                                </td>
                                <td>{{ $details['name'] }}</td>
                                <td>{{ $details['quantity'] }}</td>
                                <td> ₹{{ $details['price'] }}</td>
                                <td> ₹{{ $details['price'] * $details['quantity'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No items in cart</td>
                            </tr>
                        @endforelse
                        <tr>
                            <td colspan="4" class="text-right"><b>Total:</b></td>
                            <td> ₹{{ array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)) }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <form action="{{ route('checkout.process') }}" method="POST" class="payment-form p-4 shadow-sm rounded">
                    @csrf
                    <h5 class="mt-4 mb-3 text-center">Choose Payment Method</h5>

                    <div class="form-group row">
                        <div class="col-md-4 mb-3">
                            <label class="d-flex align-items-center">
                                <input type="radio" name="payment_method" value="credit_card" data-bg-color="#0000008f"
                                    class="mr-2" checked>
                                <img src="{{ asset('assets/Payments/credit_card_logo.jpeg') }}" alt="Credit Card"
                                    class="payment-logo"> Credit Card
                            </label>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="d-flex align-items-center">
                                <input type="radio" name="payment_method" value="paytm" data-bg-color="#17a2b8ba"
                                    id="paytm_pay_option" class="mr-2">
                                <img src="{{ asset('assets/Payments/paytm_logo.png') }}" alt="Paytm Pay"
                                    class="payment-logo">
                                Paytm
                            </label>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="d-flex align-items-center">
                                <input type="radio" name="payment_method" value="phone_pay" data-bg-color="#4f2a65cc"
                                    class="mr-2">
                                <img src="{{ asset('assets/Payments/phone_pay_logo.png') }}" alt="Phone Pay"
                                    class="payment-logo">
                                Phone Pay
                            </label>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="d-flex align-items-center">
                                <input type="radio" name="payment_method" value="paypal" data-bg-color="#1d3aff8f"
                                    class="mr-2">
                                <img src="{{ asset('assets/Payments/paypal_logo.png') }}" alt="PayPal"
                                    class="payment-logo">
                                PayPal
                            </label>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="d-flex align-items-center">
                                <input type="radio" name="payment_method" value="google_pay" data-bg-color="#4285f463"
                                    class="mr-2">
                                <img src="{{ asset('assets/Payments/google_pay_logo.png') }}" alt="Google Pay"
                                    class="payment-logo">
                                Google Pay
                            </label>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="d-flex align-items-center">
                                <input type="radio" name="payment_method" value="razor_pay" data-bg-color="#17a2b8ba"
                                    id="razor_pay_option" class="mr-2">
                                <img src="{{ asset('assets/Payments/razor_pay_logo.jpeg') }}" alt="Razor Pay"
                                    class="payment-logo">
                                Razor Pay
                            </label>
                        </div>

                    </div>

                    <button type="button" id="processPayment" class="btn btn-custom btn-block mt-4">Process
                        Payment</button>
                </form>
            @else
                <table class="table">
                    <tr>
                        <td colspan="5">No items in cart</td>
                    </tr>
                </table>
            @endif

        </div>
    </div>
    @push('scripts')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

        <script>
            document.getElementById('processPayment').addEventListener('click', function(e) {
                e.preventDefault();

                // Get the selected payment method
                var selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

                // If Razorpay is selected, handle Razorpay payment
                if (selectedMethod === 'razor_pay') {
                    var cart = @json($cart); // Cart is an object, not an array
                    var cartItems = Object.values(cart);

                    if (Array.isArray(cartItems) && cartItems.length > 0) {
                        var totalAmount = cartItems.reduce(function(total, item) {
                            return total + (item['price'] * item['quantity']);
                        }, 0) * 100; // Total in paisa

                        var products = cartItems.map(function(item) {
                            return item['name'] + " (Qty: " + item['quantity'] + ")";
                        }).join(', ');

                        var options = {
                            "key": "{{ env('RAZORPAY_KEY') }}",
                            "amount": totalAmount, // Amount in paisa
                            "currency": "INR",
                            "name": "Pizza Palooza",
                            "description": products,
                            "image": "https://as2.ftcdn.net/v2/jpg/03/21/33/93/1000_F_321339306_3UStnILdqK6xNbduTBbYido78UytJnzC.jpg",
                            "handler": function(response) {
                                // Set Razorpay payment ID in hidden field
                                var form = document.querySelector('.payment-form');
                                var input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'razorpay_payment_id';
                                input.value = response.razorpay_payment_id;
                                form.appendChild(input);

                                // Submit the form after successful Razorpay payment
                                form.submit();
                            },
                            "prefill": {
                                "name": "{{ auth()->user()->name ?? 'Guest' }}",
                                "email": "{{ auth()->user()->email ?? 'guest@example.com' }}"
                            },
                            "theme": {
                                "color": "#ff7529"
                            }
                        };

                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    } else {
                        alert('Cart is empty or not a valid array.');
                    }
                } else {
                    // For other payment methods, submit the form normally
                    document.querySelector('.payment-form').submit();
                }
            });


            //For Bg color change
            document.addEventListener('DOMContentLoaded', function() {
                var paymentForm = document.querySelector('.payment-form');
                console.log(paymentForm);
                var paymentRadios = document.querySelectorAll('input[name="payment_method"]');

                paymentRadios.forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        var bgColor = this.getAttribute('data-bg-color');
                        paymentForm.style.backgroundColor = bgColor;
                    });
                });

                var checkedRadio = document.querySelector('input[name="payment_method"]:checked');
                if (checkedRadio) {
                    paymentForm.style.backgroundColor = checkedRadio.getAttribute('data-bg-color');
                }
            });
        </script>
    @endpush
@endsection
