@extends('layouts.master')

@section('title', 'Checkout')

@section('content')
    <h2>Checkout</h2>

    @include('partials.checkout_cart')
    @include('partials.billing_address')

    @push('scripts')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer>
        </script>

        <script>
            let map;
            let marker;
            const gujaratBounds = {
                north: 24.7,
                south: 20.0,
                east: 74.3,
                west: 68.0
            };

            // Initialize the map and restrict it to Gujarat
            function initMap() {
                const gujaratCenter = {
                    lat: 22.2587,
                    lng: 71.1924
                };

                map = new google.maps.Map(document.getElementById('map'), {
                    center: gujaratCenter,
                    zoom: 8, // Suitable zoom level to show most of Gujarat
                    restriction: {
                        latLngBounds: gujaratBounds, // Restrict the map to Gujarat bounds
                        strictBounds: true, // Enforce the boundary strictly
                    },
                    mapTypeId: 'terrain' // Optional: 'roadmap', 'satellite', 'hybrid', 'terrain'
                });

                // Add click event listener to the map
                map.addListener('click', function(event) {
                    placeMarker(event.latLng);
                    setLatLng(event.latLng.lat(), event.latLng.lng());
                });
            }

            // Function to place a marker on the map
            function placeMarker(location) {
                if (marker) {
                    marker.setPosition(location);
                } else {
                    marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });
                }
            }

            // Function to set latitude and longitude
            function setLatLng(lat, lng) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                // Display selected lat/lng on the page
                document.getElementById('lat-span').textContent = 'Latitude: ' + lat;
                document.getElementById('lng-span').textContent = 'Longitude: ' + lng;
            }
            document.getElementById('processPayment').addEventListener('click', function(e) {
                e.preventDefault();
                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');

                if (!selectedPaymentMethod) {
                    document.getElementById('error-message').style.display = 'block';
                } else {
                    document.getElementById('error-message').style.display = 'none';

                    if (selectedPaymentMethod.value === 'razor_pay') {
                        var cart = @json($cartData); // Cart is an object, not an array
                        var cartItems = Object.values(cart);

                        if (Array.isArray(cartItems) && cartItems.length > 0) {
                            var totalAmount = cartItems.reduce(function(total, item) {
                                var price = item['is_premium'] ? item['discount_price'] : item['normal_price'];
                                return total + (price * item['quantity']);
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
                                "image": "https://as2.ftcdn.net/v2/jpg/03/21/33/93/1000_F_321339315_vNBRuxkrNsmGMVV7PjoXPul3OhzuL8dd.jpg",
                                "handler": function(response) {
                                    document.querySelector('.payment-form').submit();
                                },
                                "prefill": {
                                    "name": document.getElementById('fname').value,
                                    "email": document.getElementById('email').value,
                                    "contact": "" // Add user phone number if available
                                }
                            };

                            var rzp1 = new Razorpay(options);
                            rzp1.open();
                        }
                    } else {
                        document.querySelector('.payment-form').submit();
                    }
                }
            });

            document.querySelectorAll('.payment-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.payment-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    let bgColor = this.getAttribute('data-bg-color');
                    this.style.setProperty('--bg-color', bgColor);
                    this.querySelector('.payment-radio').checked = true;
                });
            });

            function selectAddress(addressId) {
                $.ajax({
                    url: '/update-selected-address', // Define this route in your backend
                    method: 'POST',
                    data: {
                        _token: $('input[name=_token]').val(),
                        address_id: addressId
                    },
                    success: function(response) {
                        // Populate form fields with the selected address details
                        $('#address_id').val(response.id);
                        $('#name').val(response.name);
                        $('#email').val(response.email);
                        $('#address').val(response.address);
                        $('#city').val(response.city);
                        $('#state').val(response.state);
                        $('#zip').val(response.zip);
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to update the selected address:', error);
                    }
                });
            }

            document.getElementById('resetFormLink').addEventListener('click', function() {
                document.getElementById('name').value = '';
                document.getElementById('email').value = '';
                document.getElementById('address').value = '';
                document.getElementById('city').value = '';
                document.getElementById('state').value = '';
                document.getElementById('zip').value = '';
            });
        </script>
    @endpush
@endsection
