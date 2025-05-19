<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            Dashboard
        </x-slot>
        <x-slot name="data">
            {{ auth()->user()->hasRole('admin') ? 
            'Admin able to view products, sales, recycle activities and their chart on this page.' : 
            'You can view your purchase products and recycle activities on this page.' 
           }}        
        </x-slot>
    </x-page-comment>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex items-stretch mx-10 gap-6 w-auto">
        @if(auth()->user()->hasRole('admin'))
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#ADEAAF] px-6">
                    <i class="fas fa-cubes fa-xl text-[#365671]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                Products
            </x-slot>
            <x-slot name="data">
                {{ $totalProducts }} <!-- Dynamically show total number of products -->
            </x-slot>
        </x-dashboard-item>
        @endif
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#f7eea0] px-6">
                    <i class="fas fa-store fa-xl text-[#ab541f]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                Order
            </x-slot>
            <x-slot name="data">
                {{ $totalOrder }}
            </x-slot>
        </x-dashboard-item>
        @if(auth()->user()->hasRole('user'))
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#faebeb] px-6">
                    <i class="fas fa-gift fa-xl text-[#e63d3d]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                Rewards
            </x-slot>
            <x-slot name="data">
                {{ $totalRewards }} <!-- Dynamically show total number of products -->
            </x-slot>
        </x-dashboard-item>
        @endif
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#B1DEFF] px-6">
                    <i class="fas fa-recycle fa-xl text-[#6f518c]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                Recycle
            </x-slot>
            <x-slot name="data">
                {{ $totalRecycleActivities }} 
            </x-slot>
        </x-dashboard-item>
    </div>

    <!-- Chart -->
    @if(auth()->user()->hasRole('admin'))
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mx-10 my-6 w-auto">
        <!-- Bar Chart -->
        <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] p-6">
            <h3 class="font-bold text-lg text-center text-gray-700 mb-4">Product Sales Quantity (Unit)</h3>
            <canvas id="productSalesChart" width="400" height="200"></canvas>
        </div>
        
        <!-- Line Chart -->
        <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] p-6">
            <h3 class="font-bold text-lg text-center text-gray-700 mb-4">Monthly Sales Amount (RM)</h3>
            <canvas id="monthlySalesChart" width="400" height="200"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mx-10 my-6 w-auto">
        <!-- Bar Chart -->
        <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] p-6">
            <h3 class="font-bold text-lg text-center text-gray-700 mb-4">Product Stock Quantity</h3>
            <canvas id="productChart" width="400" height="200"></canvas>
        </div>
        
        <!-- Pie Chart -->
        <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] p-6">
            <h3 class="font-bold text-lg text-center text-gray-700 mb-4">Total Recycle Activity</h3>
            @if(!isset($chartRecycleActivities) || $chartRecycleActivities->isEmpty())
                <p class="text-center text-gray-500 font-bold">No recycle activity data available.</p>
            @else
            <canvas id="recycleChartAdmin" style="width: 100px; height: 100px;"></canvas>
            @endif
        </div>
    </div>
    @else

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mx-10 my-6 w-auto">
        <!-- Bar Chart -->
        <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] p-6">
            <h3 class="font-bold text-lg text-center text-gray-700 mb-4">Past Purchase Product</h3>
            @if(!isset($chartPurchaseProducts) || $chartPurchaseProducts->isEmpty())
                <p class="text-center text-gray-500 font-bold">No purchase activity has been done.</p>
                <p class="text-center text-blue-700 font-semibold">You can purchase product by add to cart and checkout.</p>
            @else
                <canvas id="productPurchaseChart" width="400" height="300"></canvas>
            @endif
        </div>
        <!-- Pie Chart -->
        <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] p-6">
            <h3 class="font-bold text-lg text-center text-gray-700 mb-4">Recycle Activity</h3>
            @if(!isset($chartRecycleActivities) || $chartRecycleActivities->isEmpty())
                <p class="text-center text-gray-500 font-bold">No recycle activity has been done.</p>
                <p class="text-center text-blue-700 font-semibold">You can start one at the Recycle Activity sidebar.</p>
            @else
                <canvas id="recycleChartUser" width="400" height="200"></canvas>
            @endif
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Get product data from the server
            const productNames = @json($chartProducts->pluck('product_name'));
            const productQuantities = @json($chartProducts->pluck('product_quantity'));

            // Create the chart
            const ctx = document.getElementById('productChart').getContext('2d');
            const productChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productNames,
                    datasets: [{
                        label: 'Product Quantities',
                        data: productQuantities,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Admin Chart
            const recycleStatusAdmin = @json($chartRecycleActivities->pluck('recycle_status'));
            const recycleCountsAdmin = @json($chartRecycleActivities->pluck('total'));

            if (document.getElementById('recycleChartAdmin') && recycleStatusAdmin.length) {
                const ctxAdmin = document.getElementById('recycleChartAdmin').getContext('2d');
                new Chart(ctxAdmin, {
                    type: 'pie',
                    data: {
                        labels: recycleStatusAdmin,
                        datasets: [{
                            label: 'Recycle Activity Status',
                            data: recycleCountsAdmin,
                            backgroundColor: [
                                '#91D59B',
                                '#EDAD71',
                                'rgba(255, 206, 86, 0.2)',
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                '#FF7B00',
                                'rgba(255, 206, 86, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Recycle Activity Status (Admin)'
                            }
                        }
                    }
                });
            }

            // User Chart
            const recycleStatusUser = @json($chartRecycleActivities->pluck('recycle_status'));
            const recycleCountsUser = @json($chartRecycleActivities->pluck('total'));

            if (document.getElementById('recycleChartUser') && recycleStatusUser.length) {
                const ctxUser = document.getElementById('recycleChartUser').getContext('2d');
                new Chart(ctxUser, {
                    type: 'pie',
                    data: {
                        labels: recycleStatusUser,
                        datasets: [{
                            label: 'Recycle Activity Status',
                            data: recycleCountsUser,
                            backgroundColor: [
                                '#91D59B',
                                '#EDAD71',
                                'rgba(255, 206, 86, 0.2)',
                            ],
                            borderColor: [
                                'rgba(75, 192, 192, 1)',
                                '#FF7B00',
                                'rgba(255, 206, 86, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Recycle Activity Status (User)'
                            }
                        }
                    }
                });
            }
        });


        document.addEventListener("DOMContentLoaded", function () {
            // Product Purchase Chart Logic
            const productPurchaseNames = @json($chartPurchaseProducts->pluck('product_name'));
            const productPurchaseQuantities = @json($chartPurchaseProducts->pluck('purchase_quantity'));
            
            if (productPurchaseNames.length && productPurchaseQuantities.length) {
                const ctx = document.getElementById('productPurchaseChart').getContext('2d');
                
                // Generate random colors for each bar
                const backgroundColors = productPurchaseNames.map(() =>
                    `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.7)`
                );
                const borderColors = productPurchaseNames.map(() =>
                    `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 1)`
                );
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: productPurchaseNames,
                        datasets: [{
                            label: 'Your Purchased Quantities',
                            data: productPurchaseQuantities,
                            backgroundColor: backgroundColors, // Use dynamic colors
                            borderColor: borderColors,         // Use dynamic border colors
                            borderWidth: 1,
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            // Other chart logic (combine all into this block)
        });

        const productNames = @json($chartSalesProducts->pluck('product_name'));
        const productQuantities = @json($chartSalesProducts->pluck('total_quantity'));

        // Generate random colors for each bar
        const backgroundColors = productNames.map(() => `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 0.7)`); // Semi-transparent colors
        const borderColors = productNames.map(() => `rgba(${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, ${Math.floor(Math.random() * 256)}, 1)`); // Solid border colors

        const ctx = document.getElementById('productSalesChart').getContext('2d');
        const productChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Total Product Sales Quantity',
                    data: productQuantities,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return Math.floor(value); // Ensure only integer values are shown
                            },
                            stepSize: 1, // Ensure consistent increments of 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                return `${label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });

        //Monthly Sales Chart
        document.addEventListener("DOMContentLoaded", function () {
        // Data from the server
        const months = @json($monthlySales->pluck('month'));
        const sales = @json($monthlySales->pluck('total_sales'));

        // Create Line Chart
        const ctx = document.getElementById('monthlySalesChart').getContext('2d');
        const monthlySalesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Sales (RM)',
                    data: sales,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.3, // Adds smoothness to the line
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
                        text: 'Monthly Sales Overview'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return `RM ${value}`;
                            }
                        }
                    }
                }
            }
        });
    });


    </script>
</x-app-layout>
