@if ($selectedAddressId)
    @php
        $selectedAddress = collect($addresses)->firstWhere('id', $selectedAddressId);
    @endphp
@endif

<form action="{{ route('checkout.process') }}" method="POST" class="payment-form p-4 shadow-sm rounded">
    @csrf
    <input type="hidden" name="address_id" id="address_id" value="{{ $selectedAddressId }}">
    <div class="row p-4 border rounded">
        <div class="col-md-6">
            <!-- Billing Section -->
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-3">
                    <h3>Billing Address</h3>
                </div>
                <div class="col-lg-6 col-md-12 text-right">
                    <a href="javascript:void(0);" id="resetFormLink" class="reset-link btn">Reset Billing Address</a>
                </div>
            </div>

            <div class="form-group">
                <label for="name"><i class="fa fa-user"></i> Full Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="John M. Doe"
                    value="{{ $selectedAddress['name'] ?? '' }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email"><i class="fa fa-envelope"></i> Email</label>
                <input type="text" id="email" name="email" class="form-control" placeholder="john@example.com"
                    value="{{ $selectedAddress['email'] ?? '' }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address"><i class="fa fa-address-card-o"></i> Address</label>
                <input type="text" id="address" name="address" class="form-control"
                    placeholder="542 W. 15th Street" value="{{ $selectedAddress['address'] ?? '' }}">
                @error('address')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="city"><i class="fa fa-institution"></i> City</label>
                <input type="text" id="city" name="city" class="form-control" placeholder="New York"
                    value="{{ $selectedAddress['city'] ?? '' }}">
                @error('city')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" class="form-control" placeholder="NY"
                        value="{{ $selectedAddress['state'] ?? '' }}">
                    @error('state')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="zip">Zip</label>
                    <input type="text" id="zip" name="zip" class="form-control" placeholder="10001"
                        value="{{ $selectedAddress['zip'] ?? '' }}">
                    @error('zip')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="sameadr" name="sameadr" value="1"
                    {{ old('sameadr') ? 'checked' : '' }}>
                <label class="form-check-label" for="sameadr">Shipping address same as billing</label>
                @error('sameadr')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Google Maps iframe -->
        <div class="col-md-6">
            {{-- <div id="map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11838341.437697625!2d67.79358063316689!3d22.69444912837667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e84f7228aeaf5%3A0x1a8c5c00fdf99888!2sGujarat%2C%20India!5e0!3m2!1sen!2sus!4v1695811175861!5m2!1sen!2sus"
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div> --}}
            <div id="selected-location" style="margin-top: 10px;">
                <strong>Selected Location:</strong>
                <span id="lat-span">Latitude: -</span>,
                <span id="lng-span">Longitude: -</span>
            </div>
            <div id="map" style="height: 400px; width: 100%;"></div>
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        </div>
    </div>

    <!-- Payment Methods Section -->
    <div class="row">
        <div class="col-md-12">
            <h5 class="mt-4 mb-3 text-center">Choose Payment Method</h5>
            <div class="form-group row text-center">
                <div class="col-md-4 mb-3">
                    <div class="payment-card p-3 border rounded shadow-sm" data-bg-color="#0000008f">
                        <input type="radio" name="payment_method" value="credit_card" selected
                            class="payment-radio">
                        <img src="{{ asset('assets/Payments/credit_card_logo.jpeg') }}" alt="Credit Card"
                            class="payment-logo mb-1">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="payment-card p-3 border rounded shadow-sm" data-bg-color="#17a2b8ba">
                        <input type="radio" name="payment_method" value="paytm" class="payment-radio">
                        <img src="{{ asset('assets/Payments/paytm_logo.png') }}" alt="Paytm Pay"
                            class="payment-logo mb-1">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="payment-card p-3 border rounded shadow-sm" data-bg-color="#4f2a65cc">
                        <input type="radio" name="payment_method" value="phone_pay" class="payment-radio">
                        <img src="{{ asset('assets/Payments/phone_pay_logo.png') }}" alt="Phone Pay"
                            class="payment-logo mb-1">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="payment-card p-3 border rounded shadow-sm" data-bg-color="#1d3aff8f">
                        <input type="radio" name="payment_method" value="paypal" class="payment-radio">
                        <img src="{{ asset('assets/Payments/paypal_logo.png') }}" alt="PayPal"
                            class="payment-logo mb-1">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="payment-card p-3 border rounded shadow-sm" data-bg-color="#4285f463">
                        <input type="radio" name="payment_method" value="google_pay" class="payment-radio">
                        <img src="{{ asset('assets/Payments/google_pay_logo.png') }}" alt="Google Pay"
                            class="payment-logo mb-1">
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="payment-card p-3 border rounded shadow-sm" data-bg-color="#17a2b8ba">
                        <input type="radio" name="payment_method" value="razor_pay" class="payment-radio">
                        <img src="{{ asset('assets/Payments/razor_pay_logo.jpeg') }}" alt="Razor Pay"
                            class="payment-logo mb-1">
                    </div>
                </div>
            </div>

            <!-- Error message -->
            <p id="error-message" class="text-danger" style="display: none;">Please select a payment method.</p>

            <!-- Submit Button -->
            <button type="submit" id="processPayment" class="btn btn-success btn-block">Continue to
                checkout</button>
        </div>
    </div>
</form>
