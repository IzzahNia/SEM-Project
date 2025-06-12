<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            Page Description
        </x-slot>
        <x-slot name="data">
            {{ auth()->user()->hasRole('admin') ?
            'Admin able to view payment on this page.' :
            'You can view the payment on this page.'
           }}
        </x-slot>
    </x-page-comment>

    <!-- Revenue Chart -->
    <div class="gap-6 mx-10 my-6 w-auto">
        @if(auth()->user()->hasRole('admin'))
        <div class="flex justify-between items-center mb-4">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <label for="revenueFilter" class="font-bold text-md">Revenue Bar</label>
            <select id="revenueFilter" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold">
                <option value="day">Daily</option>
                <option value="month" selected>Monthly</option>
                <option value="year">Yearly</option>
            </select>
            <select id="sortOrder" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold">
                <option value="asc" selected>Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </div>
        <div class="rounded-md bg-white drop-shadow-md p-6">
            <h3 class="font-bold text-lg text-center text-gray-700 mb-4"></h3>
            <canvas id="revenueChart" width="500" height="100"></canvas>
        </div>
        @endif
    </div>

    <!-- Table Filters and Controls -->


    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
        <div class="col-span-3">
            <div class="flex justify-between items-center w-auto mb-2">
                <p class="font-bold text-md">Payment Lists</p>
                <div class="grid grid-cols-1 gap-4 mx-10 mb-4">
        <div class="flex gap-4 items-center">
    <input type="text" id="searchInput" placeholder="Search"
        class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring {{ auth()->user()->hasRole('admin') ? 'focus:ring-primary-500 focus:border-primary-500' : 'focus:ring-purple-500 focus:border-purple-500' }}">

    <select id="sortAmount" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold">
        <option value="">Sort by Total</option>
        <option value="high">Highest to Lowest</option>
        <option value="low">Lowest to Highest</option>
    </select>

    <select id="sortDate" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold">
        <option value="">Sort by Date</option>
        <option value="latest">Latest to Oldest</option>
        <option value="oldest">Oldest to Latest</option>
    </select>

    <select id="filterType" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold">
        <option value="">Filter by Payment Type</option>
        <option value="Cash">Cash</option>
        <option value="Online Payment">Online Payment</option>
    </select>

    <button id="resetFilters" class="py-2 px-4 bg-gray-200 hover:bg-gray-300 rounded-md text-sm font-semibold">
        Reset Filters
    </button>
</div>

    </div>
            </div>

            <x-show-table :headers="['Payment ID', 'Total (RM)', 'Date', 'Order ID', 'Customer', 'Status', 'Payment Type', 'Action']">
                <tbody id="paymentTableBody" class="w-full">
                @php
                    $sortedPayments = $payments->sortBy(function ($payment) {
                        return $payment->payment_status === 'Pending' ? 0 : 1;
                    });
                @endphp
                @foreach ($sortedPayments as $i => $payment)
                    <tr class="flex px-8 py-2 {{ auth()->user()->hasRole('admin') ? (($loop->index % 2 == 0) ? 'bg-primary-50' : '') : (($loop->index % 2 == 0) ? 'bg-purple-50' : '') }}"
                        data-total="{{ $payment->payment_amount }}"
                        data-date="{{ $payment->created_at }}"
                        data-type="{{ $payment->payment_type }}"
                        data-name="{{ strtolower($payment->order->user->name) }}"
                        data-status="{{ $payment->payment_status }}">
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
            <div id="paginationControls" class="flex justify-center mt-4 gap-2"></div>
        </div>
    </div>
</x-app-layout>

<script>
    const tableBody = document.getElementById('paymentTableBody');
    const rows = Array.from(tableBody.querySelectorAll('tr'));
    const searchInput = document.getElementById('searchInput');
    const sortAmount = document.getElementById('sortAmount');
    const sortDate = document.getElementById('sortDate');
    const filterType = document.getElementById('filterType');
    const resetBtn = document.getElementById('resetFilters');
    const paginationControls = document.getElementById('paginationControls');

    let currentPage = 1;
    const rowsPerPage = 10;

     // Revenue Chart Setup
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
                        legend: { display: true, position: 'top' },
                        title: { display: true, text: 'Revenue Overview' }
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

        document.getElementById('revenueFilter').addEventListener('change', () => {
            const groupBy = document.getElementById('revenueFilter').value;
            const sortOrder = document.getElementById('sortOrder').value;

            fetch(`/payment-list?group_by=${groupBy}&sort_order=${sortOrder}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => initializeChart(data))
            .catch(error => console.error('Error fetching revenue data:', error));
        });

        const initialData = @json($revenueData);
        initializeChart(initialData);

    function paginate(array, page, perPage) {
        const offset = (page - 1) * perPage;
        return array.slice(offset, offset + perPage);
    }

    function updatePagination(totalPages) {
        paginationControls.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300'}`;
            btn.addEventListener('click', () => {
                currentPage = i;
                filterAndSortTable();
            });
            paginationControls.appendChild(btn);
        }
    }

    function filterAndSortTable() {
        let filtered = [...rows];

        const type = filterType.value;
        if (type) {
            filtered = filtered.filter(row => row.dataset.type === type);
        }

        const search = searchInput.value.toLowerCase();
        if (search) {
            filtered = filtered.filter(row => row.dataset.name.includes(search));
        }

        const pendingRows = filtered.filter(row => row.dataset.status === 'Pending');
        const paidRows = filtered.filter(row => row.dataset.status !== 'Pending');

        const sortRows = (list) => {
            if (sortAmount.value === 'high') {
                list.sort((a, b) => b.dataset.total - a.dataset.total);
            } else if (sortAmount.value === 'low') {
                list.sort((a, b) => a.dataset.total - b.dataset.total);
            }

            if (sortDate.value === 'latest') {
                list.sort((a, b) => new Date(b.dataset.date) - new Date(a.dataset.date));
            } else if (sortDate.value === 'oldest') {
                list.sort((a, b) => new Date(a.dataset.date) - new Date(b.dataset.date));
            }

            return list;
        };

        const sortedPending = sortRows(pendingRows);
        const sortedPaid = sortRows(paidRows);

        const combined = [...sortedPending, ...sortedPaid];
        const totalPages = Math.ceil(combined.length / rowsPerPage);
        updatePagination(totalPages);

        const pageRows = paginate(combined, currentPage, rowsPerPage);
        tableBody.innerHTML = '';
        pageRows.forEach(row => tableBody.appendChild(row));
    }

    [searchInput, sortAmount, sortDate, filterType].forEach(input => {
        input.addEventListener('input', () => {
            currentPage = 1;
            filterAndSortTable();
        });
    });

    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        sortAmount.value = '';
        sortDate.value = '';
        filterType.value = '';
        currentPage = 1;
        filterAndSortTable();
    });

    // Initialize
    filterAndSortTable();
</script>

