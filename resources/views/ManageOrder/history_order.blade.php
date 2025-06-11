<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            My Purchase History
        </x-slot>
        <x-slot name="data">
            Below is the list of your completed purchases. You can click on any order to view its details.
        </x-slot>
    </x-page-comment>

    <div class="grid grid-cols-1 gap-6 mx-10 my-6">
        <div>
            <p class="font-bold text-md mb-4">Completed Orders</p>

            @if($orders->isEmpty())
                <div class="text-gray-500 text-sm">You have no completed orders.</div>
            @else
                 <x-show-table :headers="['Order ID', 'Customer Name', 'Total (RM)', 'Date', 'Status', 'Action']">
                <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                    @foreach ($orders->where('order_status', 'Completed') as $i => $order)
                        <tr class="flex px-8 py-2 {{ auth()->user()->hasRole('admin') ? (($loop->index % 2 == 0) ? 'bg-primary-50' : '') : (($loop->index % 2 == 0) ? 'bg-purple-50' : '') }}">
                            <td class="mx-4 py-2 text-gray text-sm font-semibold w-4">{{ $loop->iteration }}.</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $order->id }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $order->order_total_price }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ date('Y-m-d H:i:s', strtotime($order->order_datetime)) }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ ucfirst($order->order_status) }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">
                                 @if(auth()->user()->hasRole('admin'))
                                    @if ($order->order_status == 'Completed')
                                    <a href="{{ route('view.order', $order->id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 hover:bg-blue-200 ml-2">
                                        <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                    </a>
                                    @else
                                    <a href="{{ route('edit.order', $order->id) }}" class="rounded-full py-2 px-3 bg-green-100 border border-green-200 hover:bg-green-200 ml-2">
                                        <i class="fa-regular fa-pen text-green-500 fa-sm"></i>
                                    </a>
                                        <button type="button" data-modal-target="popup-modal-[{{ $i }}]" data-modal-toggle="popup-modal-[{{ $i }}]" class="rounded-full py-2 px-3 bg-red-50 border border-red-200 justify-center items-center hover:bg-red-100 ml-2"><i class="fa-regular fa-trash-can text-red-500 fa-sm"></i></button>
                                        <div class="mx-50 space-x-50 flex justify-center items-center">
                                    <a href="{{ route('order.progress') }}"><x-button>Order Progress</x-button></a>
                                </div>
                                    @endif
                                @else

                                    <a href="{{ route('view.order', $order->id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 hover:bg-blue-200 ml-2">
                                        <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                    </a>
                                @endif
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-show-table>
            @endif
        </div>
    </div>
</x-app-layout>
