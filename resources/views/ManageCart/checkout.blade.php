<x-app-layout>
    <form action="{{ route('place.order') }}" method="POST">
        @csrf
        <div id="checkout-cart-content">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <h2 class="font-semibold text-2xl">Checkout</h2>
                    <p><strong>Customer:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Contact:</strong> {{ auth()->user()->contact_number }}</p>
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                </div>
                <div>
                    <img src="{{ asset('img/plasticware_logo.png') }}" alt="Plasticware Logo" style="width: 120px; height: auto; margin-left: 100px;">
                </div>
            </div>

            <table class="min-w-full border border-gray-200 mt-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Product</th>
                        <th class="border p-2">Quantity</th>
                        <th class="border p-2">Price (RM)</th>
                        <th class="border p-2">Amount (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                    <tr>
                        <td class="border p-2">{{ $item->product->product_name }}</td>
                        <td class="border p-2">{{ $item->quantity }}</td>
                        <td class="border p-2">{{ number_format($item->product->product_selling_price, 2) }}</td>
                        <td class="border p-2">{{ number_format($item->quantity * $item->product->product_selling_price, 2) }}</td>
                    </tr>
                    <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
                    <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                    <input type="hidden" name="qty[]" value="{{ $item->quantity }}">
                    <input type="hidden" name="price[]" value="{{ $item->product->product_selling_price }}">
                    <input type="hidden" name="amount[]" value="{{ $item->quantity * $item->product->product_selling_price }}">
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right font-bold p-2">Total</td>
                        <td class="font-bold p-2">RM {{ number_format($totalAmount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <!-- Hidden fields for totalAmount -->
            <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
        </div>

        <!-- Payment Method -->
        <div class="mt-6 px-4">
            <label for="payment_type" class="font-semibold text-sm text-gray mb-2">Payment Method</label>
            <div class="flex space-x-6 mt-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="payment_type" value="Cash" class="form-radio text-blue-600" required>
                    <span class="ml-2 text-sm">Cash</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="payment_type" value="Online Payment" class="form-radio text-blue-600" required>
                    <span class="ml-2 text-sm">Online Payment</span>
                </label>
            </div>
            @error('payment_type')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end mt-4 px-4">
            <a href="{{ route('cart.list') }}"><x-secondary-button class="mr-2">CANCEL</x-secondary-button></a>
            <x-button type="submit">Place Order</x-button>
        </div>
    </form>
</x-app-layout>
