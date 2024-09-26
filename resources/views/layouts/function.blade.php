@push('scripts')
    <script>
        $('.btn-plus').on('click', function() {
            var id = $(this).data('id');
            updateQuantity(id, 1); // Increment quantity by 1
        });

        // Minus button click handler
        $('.btn-minus').on('click', function() {
            var id = $(this).data('id');
            updateQuantity(id, -1); // Decrement quantity by 1
        });

        // Function to update quantity
        function updateQuantity(id, change) {
            var inputField = $('.quantity-input[data-id="' + id + '"]');
            var currentQty = parseInt(inputField.val());
            var newQty = currentQty + change;

            // Ensure the quantity doesn't go below 1
            if (newQty >= 1) {
                $.ajax({
                    url: '{{ route('cart.update') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        quantity: newQty
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the quantity value
                            inputField.val(newQty);

                            // Disable minus button if the quantity is 1
                            if (newQty === 1) {
                                $('.btn-minus[data-id="' + id + '"]').prop('disabled', true);
                            } else {
                                $('.btn-minus[data-id="' + id + '"]').prop('disabled', false);
                            }

                            // Update item total
                            var itemTotal = response.itemTotal;
                            $('.item-total[data-id="' + id + '"]').text('₹' + itemTotal.toFixed(2));

                            // Update cart subtotal
                            $('#cart-subtotal').text('₹' + response.cartTotal.toFixed(2));
                        }
                    }
                });
            }
        }
    </script>
@endpush
