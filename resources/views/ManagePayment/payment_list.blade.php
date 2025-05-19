<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            Page Description
        </x-slot>
        <x-slot name="data">
            {{ auth()->user()->hasRole('admin') ? 
            'Admin able to view payemnt on this page.' : 
            'You can view the payment on this page.' 
           }}        
        </x-slot>
    </x-page-comment>

    <!-- Chart -->
    <div class="gap-6 mx-10 my-6 w-auto">
        @if(auth()->user()->hasRole('admin'))
        <!-- Line Chart -->
        <div class="flex justify-between items-center mb-4">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <label for="revenueFilter" class="font-bold text-md">Revenue Bar</label>
            <select id="revenueFilter" class="ml-2 border rounded p-2">
                <option value="day">Daily</option>
                <option value="month" selected>Monthly</option>
                <option value="year">Yearly</option>
            </select>
            <select id="sortOrder" class="ml-2 border rounded p-2">
                <option value="asc" selected>Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </div>
        <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] p-6">
            <h3 class="font-bold text-lg text-center text-gray-700 mb-4"></h3>
            <canvas id="revenueChart" width="500" height="100"></canvas>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
        <div class="col-span-3">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">Payment Lists</p>
                <div>
                    <input type="text" id="searchInput" placeholder="Search" 
                    class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring {{ auth()->user()->hasRole('admin') ? 'focus:ring-primary-500 focus:border-primary-500' : 'focus:ring-purple-500 focus:border-purple-500' }}">
                </div>               
            </div>
            <x-show-table :headers="['Payment ID', 'Total (RM)', 'Date', 'Order ID', 'Customer', 'Status', 'Payment Type', 'Action']">
                <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                    @foreach ($payments->filter(function ($payment) {
                        return auth()->user()->hasRole('admin') || $payment->payment_status === 'Paid';
                    }) as $i => $payment)
                        <tr class="flex px-8 py-2 {{ auth()->user()->hasRole('admin') ? (($loop->index % 2 == 0) ? 'bg-primary-50' : '') : (($loop->index % 2 == 0) ? 'bg-purple-50' : '') }}">
                            <td class="mx-4 py-2 text-gray text-sm font-semibold w-4">{{ $loop->iteration }}.</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $payment->id }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $payment->payment_amount }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $payment->created_at->format('d/m/Y') }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $payment->order->id }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $payment->order->user->name }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $payment->payment_status }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $payment->payment_type }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">
                                <a href="{{ route('view.payment', $payment->order_id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 justify-center items-center hover:bg-blue-200 ml-2">
                                    <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </x-show-table>            
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        let revenueChart = null;

        function initializeChart(data) {
            if (revenueChart) {
                revenueChart.destroy();
            }

            revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.period),
                    datasets: [{
                        label: 'Revenue (RM)',
                        data: data.map(item => item.total_revenue),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Revenue Overview'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return `RM ${value}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateChart() {
            const groupBy = document.getElementById('revenueFilter').value;
            const sortOrder = document.getElementById('sortOrder').value;

            fetch(`/payment-list?group_by=${groupBy}&sort_order=${sortOrder}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                initializeChart(data);
            })
            .catch(error => console.error('Error fetching revenue data:', error));
        }

        document.getElementById('revenueFilter').addEventListener('change', updateChart);
        document.getElementById('sortOrder').addEventListener('change', updateChart);

        // Initialize the chart with existing data
        const initialData = @json($revenueData);
        initializeChart(initialData);
    });
</script>
