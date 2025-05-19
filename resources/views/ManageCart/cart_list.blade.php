<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            Cart List
        </x-slot>
        <x-slot name="data">
            You can choose which products in the cart to checkout.        
        </x-slot>
    </x-page-comment>
    <div class="mx-10">
        {{-- Check if the cart is empty --}}        
        @if($carts->isEmpty())
        <div class="my-5">
            <p class="font-semibold">Your cart is empty. Please add items to the cart before proceeding.</p>
            <div class="my-5">
            <a href="{{ route('product') }}" class="font-bold text-purple-900 underline mt-15">Go back to products</a>
            </div>
        </div>
        @else
            <form action="{{ route('checkout.list') }}" method="GET">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="p-3 border border-gray-300">
                                <input type="checkbox" id="select-all" />
                            </th>
                            <th class="p-3 border border-gray-300">Image</th>
                            <th class="p-3 border border-gray-300">Product Name</th>
                            <th class="p-3 border border-gray-300">Price Per Unit</th>
                            <th class="p-3 border border-gray-300">Quantity</th>
                            <th class="p-3 border border-gray-300">Price Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carts as $cart)
                            <tr>
                                <td class="p-3 border border-gray-300">
                                    <input type="checkbox" name="cart_ids[]" class="cart-checkbox" value="{{ $cart->id }}" data-amount="{{ $cart->product->product_selling_price * $cart->quantity }}">
                                </td>
                                <td class="p-3 border border-gray-300">
                                    <img src="{{ asset('images/products/' . $cart->product->product_image) }}" alt="{{ $cart->product->product_name }}" class="h-16 w-16 object-cover">
                                </td>
                                <td class="p-3 border border-gray-300">{{ $cart->product->product_name }}</td>
                                <td class="p-3 border border-gray-300">RM {{ number_format($cart->product->product_selling_price, 2) }}</td>
                                <td class="p-3 border border-gray-300">
                                    <div class="flex items-center space-x-2">
                                        <button type="button" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md decrement-btn" data-id="{{ $cart->id }}">-</button>
                                        <input 
                                        type="number" 
                                        id="quantity-{{ $cart->id }}" 
                                        name="quantity" 
                                        data-id="{{ $cart->id }}" 
                                        data-price="{{ $cart->product->product_selling_price }}" 
                                        data-max="{{ $cart->product->product_quantity }}" 
                                        min="1" 
                                        max="{{ $cart->product->product_quantity }}" 
                                        value="{{ $cart->quantity }}" 
                                        class="quantity-input">                                    
                                        <button type="button" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md increment-btn" data-id="{{ $cart->id }}">+</button>
                                        <input type="hidden" id="hidden-quantity-{{ $cart->id }}" name="hidden-quantity" value="{{ $cart->quantity }}">
                                    </div>
                                </td>
                                <td class="p-3 border border-gray-300">
                                    RM <span id="amount-{{ $cart->id }}">{{ number_format($cart->product->product_selling_price * $cart->quantity, 2) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between items-center mt-6">
                <div>
                    <strong>Total Amount:</strong> RM <span id="total-amount">0.00</span>
                </div>
                <x-button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Proceed to Checkout</x-button>
            </div>
        </form>
        @endif
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const incrementBtns = document.querySelectorAll('.increment-btn');
        const decrementBtns = document.querySelectorAll('.decrement-btn');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const totalAmountElement = document.getElementById('total-amount');
        const checkboxes = document.querySelectorAll('.cart-checkbox');
        const selectAllCheckbox = document.getElementById('select-all');

        const updateAmounts = (cartId, quantity) => {
            // Get the price per unit
            const pricePerUnit = parseFloat(document.querySelector(`#quantity-${cartId}`).getAttribute('data-price'));
            const amountElement = document.getElementById(`amount-${cartId}`);

            // Calculate the new amount
            const newAmount = pricePerUnit * quantity;
            amountElement.textContent = newAmount.toFixed(2);

            // Update checkbox data
            const checkbox = document.querySelector(`.cart-checkbox[value="${cartId}"]`);
            if (checkbox) {
                checkbox.dataset.amount = newAmount;
            }

            // Update total amount
            updateTotalAmount();
        };

        const updateTotalAmount = () => {
            let total = 0;
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.amount) || 0;
                }
            });
            totalAmountElement.textContent = total.toFixed(2);
        };

        const saveQuantityToDatabase = async (cartId, newQuantity) => {
            try {
                const response = await fetch('{{ route('cart.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ cart_id: cartId, quantity: newQuantity }),
                });

                const data = await response.json();
                if (data.status !== 'success') {
                    alert('Failed to update quantity in the database.');
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
                alert('An error occurred while updating the quantity.');
            }
        };

        incrementBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const cartId = this.dataset.id;
                const quantityInput = document.querySelector(`#quantity-${cartId}`);
                const maxQuantity = parseInt(quantityInput.getAttribute('data-max'));
                let currentQuantity = parseInt(quantityInput.value);

                if (currentQuantity < maxQuantity) {
                    currentQuantity++;
                    quantityInput.value = currentQuantity;
                    updateAmounts(cartId, currentQuantity);
                    saveQuantityToDatabase(cartId, currentQuantity);
                } else {
                    alert(`Maximum stock available is ${maxQuantity}.`);
                }
            });
        });

        decrementBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const cartId = this.dataset.id;
                const quantityInput = document.querySelector(`#quantity-${cartId}`);
                let currentQuantity = parseInt(quantityInput.value);

                if (currentQuantity > 0) {
                    currentQuantity--;
                    quantityInput.value = currentQuantity;

                    if (currentQuantity === 0) {
                        // Call API to delete the cart item
                        deleteCartItem(cartId);
                        // Remove the table row for the deleted item
                        const row = this.closest('tr');
                        if (row) row.remove();
                    } else {
                        updateAmounts(cartId, currentQuantity);
                        saveQuantityToDatabase(cartId, currentQuantity);
                    }
                }
            });
        });

        const deleteCartItem = async (cartId) => {
            try {
                const response = await fetch('{{ route('cart.delete') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ cart_id: cartId }),
                });

                const data = await response.json();
                if (data.status === 'success') {
                    updateTotalAmount();
                } else {
                    alert('Failed to delete the item from the cart.');
                }
            } catch (error) {
                console.error('Error deleting cart item:', error);
                alert('An error occurred while deleting the item.');
            }
        };

        quantityInputs.forEach((input) => {
            input.addEventListener('input', function () {
                const cartId = this.dataset.id;
                const maxQuantity = parseInt(this.getAttribute('data-max'));
                let quantity = parseInt(this.value);

                if (quantity > maxQuantity) {
                    alert(`Maximum stock available is ${maxQuantity}.`);
                    quantity = maxQuantity;
                    this.value = maxQuantity;
                } else if (quantity < 1 || isNaN(quantity)) {
                    quantity = 1;
                    this.value = 1;
                }

                updateAmounts(cartId, quantity);
                saveQuantityToDatabase(cartId, quantity);
            });
        });

        const updateSelectAllCheckbox = () => {
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            selectAllCheckbox.checked = allChecked;
        };

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', function () {
                // If any checkbox is unchecked, untick the select-all checkbox
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                }
                updateTotalAmount();
                updateSelectAllCheckbox(); // Check if all checkboxes are selected
            });
        });

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
                updateTotalAmount();
                updateSelectAllCheckbox(); // Update select-all checkbox after change
            });
        }
    });
</script>