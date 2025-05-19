<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center w-full">
            <p class="font-bold text-md">Order Details</p>
        </div>
    </div>

    <!-- Order Details Section -->
    <div class="mt-2 mb-4 mx-4">

        <!-- Printable Order Content -->
        <div id="printable-order-content">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                <div>
                    <h2 class="font-semibold text-2xl">Order #{{ $order->id }}</h2>
                    <p><strong>Customer :</strong> {{ $order->user->name }}</p>
                    <p><strong>Contact :</strong> {{ $order->user->contact_number }}</p>
                    <p><strong>Email :</strong> {{ $order->user->email }}</p>
                    <p><strong>Status:</strong> {{ $order->order_status }}</p>
                    <p><strong>Payment Method:</strong> {{ $order->payment->payment_type }}</p>
                    <p><strong>Payment Status:</strong> {{ $order->payment->payment_status }}</p>
                </div>
                
                <div>
                    <img src="{{ asset('img/HLH-logo.png') }}" alt="HLH Logo" style="width: 120px; height: auto; margin-left: 100px;">
                    <img src="{{ asset('img/plasticware_logo.png') }}" alt="Plasticware Logo" style="width: 120px; height: auto; margin-left: 100px;">
                    <p><strong>Order Date:</strong> {{ $order->order_datetime }}</p>
                </div>
            </div>

            <table class="min-w-full border border-gray-200 mt-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border p-2">Product</th>
                        <th class="border p-2">Quantity</th>
                        <th class="border p-2">Price (RM)</th>
                        <th class="border p-2">Discount (%)</th>
                        <th class="border p-2">Amount (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="border p-2">{{ $item->product->product_name }}</td>
                        <td class="border p-2">{{ $item->order_item_quantity }}</td>
                        <td class="border p-2">{{ number_format($item->order_item_price, 2) }}</td>
                        <td class="border p-2">{{ number_format($item->order_item_discount) }}</td>
                        <td class="border p-2">{{ number_format($item->order_item_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right font-bold p-2">Total</td>
                        <td class="font-bold p-2">{{ number_format($order->order_total_price, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Print and Back Buttons -->
        <div class="flex justify-end mt-12 px-4">
            <a href="{{ route('order.list') }}"><x-secondary-button class="mr-2">CANCEL</x-secondary-button></a>
            <x-button onclick="window.print()">Print Order</x-button>
        </div>
    </div>

    <!-- Custom Styles for Print -->
    <style>
        @media print {
            .no-print { display: none; }
            body * { visibility: hidden; }
            #printable-order-content, #printable-order-content * { visibility: visible; }
            #printable-order-content { position: absolute; left: 0; top: 0; width: 100%; }
        }
    </style>
</x-app-layout>
