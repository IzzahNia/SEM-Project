<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            Order Progress
        </x-slot>
        <x-slot name="data">
            You can view your latest order progress here.
        </x-slot>
    </x-page-comment>

    @if($order)
        <div class="flex justify-center mt-5 mx-10 px-4 py-4 bg-white text-gray rounded-md drop-shadow-[0px_0px_24px_rgba(120,120,120,0.15)]">
            <!-- Order Progress Bar -->
            <div class="w-full max-w-3xl">
                <div class="flex flex-col justify-between">
                    <!-- Logo at the top (centered) -->
                    <div class="flex justify-left mt-2 mb-5">
                        <div class="flex items-center rounded-md pl-1 pr-4">
                            <i class="fas fa-clipboard-list fa-xl text-[#655c5c]"></i>
                        </div>
                        <p class="text-lg font-semibold text-black pr-2">
                            ORDER
                        </p>
                        <p class="text-lg font-semibold text-[#492bab]">
                            #{{$order->id}}
                        </p>
                    </div>
                </div>
                <div class="flex justify-between items-center my-5">
                    <!-- Order Received -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full {{ $order->order_status == 'Pending' || $order->order_status == 'Wait Payment' || $order->order_status == 'Completed' ? 'bg-blue-500' : 'bg-gray-300' }} flex items-center justify-center text-white">
                            <i class="fas fa-box"></i>
                        </div>
                        <p class="mt-2 text-sm {{ $order->order_status == 'Pending' || $order->order_status == 'Wait Payment' || $order->order_status == 'Completed' ? 'text-blue-500' : 'text-gray-400' }}">Order Received</p>
                    </div>

                    <div class="flex-grow border-t-2 {{ $order->order_status == 'Pending' || $order->order_status == 'Wait Payment' || $order->order_status == 'Completed' ? 'border-blue-500' : 'border-gray-300' }}"></div>

                    <!-- Wait Payment -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full {{ $order->order_status == 'Wait Payment' || $order->order_status == 'Completed' ? 'bg-blue-500' : 'bg-gray-300' }} flex items-center justify-center text-white">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <p class="mt-2 text-sm {{ $order->order_status == 'Wait Payment' || $order->order_status == 'Completed' ? 'text-blue-500' : 'text-gray-400' }}">Payment Completed</p>
                    </div>

                    <div class="flex-grow border-t-2 {{ $order->order_status == 'Wait Payment' || $order->order_status == 'Completed' ? 'border-blue-500' : 'border-gray-300' }}"></div>

                    <!-- Order Completed -->
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full {{ $order->order_status == 'Completed' ? 'bg-blue-500' : 'bg-gray-300' }} flex items-center justify-center text-white">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <p class="mt-2 text-sm {{ $order->order_status == 'Completed' ? 'text-blue-500' : 'text-gray-400' }}">Order Completed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details Section -->
        <div class="mt-4 mb-4 mx-10">
            <!-- Printable Order Content -->
            <div id="printable-order-content">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                    <div>
                        <p><strong>Order Date:</strong> {{ $order->order_datetime }}</p>
                        <p><strong>Order Status:</strong> {{ $order->order_status }}</p>
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
        </div>
    @else
        <div class="flex justify-center items-center h-32 mx-10 bg-white text-gray-700 rounded-md shadow">
            <p class="text-lg font-semibold">No orders right now.</p>
        </div>
    @endif
</x-app-layout>
