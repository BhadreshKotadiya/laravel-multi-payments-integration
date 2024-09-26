<div class="row">
    <!-- Cart Summary (Left Section) -->
    <div class="col-lg-8 col-md-12">
        <div class="p-4 border rounded">
            <h4>
                <span class="price" style="color:black">
                    <i class="fa fa-shopping-cart"></i> <b>{{ count($cartData) }}</b>
                </span>
            </h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Items</th>
                        <th style="width:200px;" class="text-center">Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cartData as $id => $details)
                        @php
                            $price = $details['is_premium'] ? $details['discount_price'] : $details['normal_price'];
                            $itemTotal = $price * $details['quantity'];
                        @endphp
                        <tr>
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
                            <td> ₹{{ number_format($price, 2) }}</td>
                            <td class="item-total" data-id="{{ $id }}">
                                ₹{{ number_format($itemTotal, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No items in cart</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="3" class="text-right"><b>Total:</b></td>
                        <td class="price" id="cart-subtotal">
                            ₹{{ number_format(
                                array_sum(
                                    array_map(function ($item) {
                                        $price = $item['is_premium'] ? $item['discount_price'] : $item['normal_price'];
                                        return $price * $item['quantity'];
                                    }, $cartData),
                                ),
                                2,
                            ) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
        <div class="p-4 border rounded">
            <h3>Select Delivery Address</h3>
            <div class="addresses-section" style="max-height: 300px; overflow-y: auto;">
                <form id="address-form">
                    @csrf
                    @foreach ($addresses as $address)
                        <div class="form-check">
                            <input type="radio" name="address_id" value="{{ $address['id'] }}"
                                class="form-check-input" id="address-{{ $address['id'] }}"
                                {{ $address['selected_address'] ? 'checked' : '' }}
                                onchange="selectAddress({{ $address['id'] }})">
                            <label class="form-check-label" for="address-{{ $address['id'] }}">
                                <strong>Name:</strong> {{ $address['name'] }}<br>
                                <strong>Email:</strong> {{ $address['email'] }}<br>
                                <strong>Address:</strong> {{ $address['address'] }}<br>
                                <strong>City:</strong> {{ $address['city'] }}<br>
                                <strong>State:</strong> {{ $address['state'] }}<br>
                                <strong>Zip Code:</strong> {{ $address['zip'] }}<br>
                            </label>
                        </div>
                        <hr>
                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>
