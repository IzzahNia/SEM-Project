<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            New Order List
        </x-slot>
        <x-slot name="data">
            {{ auth()->user()->hasRole('admin') ?
            'This New Order List is from the customer. Admin is able to manage the orders by updating the status (Completed or Canceled).' :
            'You create, edit, view and pay the orders on this page.'
           }}
        </x-slot>
    </x-page-comment>

    @if(auth()->user()->hasRole('admin'))
    <div class="mx-10">

    </div>
    @endif

    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
        <div class="col-span-3">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">New Order List</p>
                <div class="flex items-center space-x-4">
                    <!-- Primary Sorting Dropdown -->
                    <select id="primarySort" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold">
                        <option value="">Sort By</option>
                        <option value="all">All</option>
                        <option value="total_asc">Total (Low to High)</option>
                        <option value="total_desc">Total (High to Low)</option>
                        <option value="date_asc">Date (Newest first)</option>
                        <option value="date_desc">Date (Oldest first)</option>
                    </select>

                    <!-- Status Filtering Dropdown -->
                    <select id="statusFilter" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold {{ auth()->user()->hasRole('admin') ? 'focus:ring-primary-500 focus:border-primary-500' : 'focus:ring-purple-500 focus:border-purple-500' }}">
                        <option value="">Filter by Status</option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="canceled">Canceled</option>
                    </select>

                    <!-- Search Input -->
                    <input type="text" id="searchInput" placeholder="Search"
                    class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring {{ auth()->user()->hasRole('admin') ? 'focus:ring-primary-500 focus:border-primary-500' : 'focus:ring-purple-500 focus:border-purple-500' }}">
                </div>
            </div>

            <x-show-table :headers="['Order ID', 'Customer Name', 'Total (RM)', 'Date', 'Status', 'Action']">
                <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                    @foreach ($orders as $i => $order)
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
                                        <a href="{{ route('view.order', $order->id) }}"
                                        class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 hover:bg-blue-200">
                                            <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('edit.order', $order->id) }}"
                                        class="rounded-full py-2 px-3 bg-green-100 border border-green-200 hover:bg-green-200">
                                            <i class="fa-regular fa-pen text-green-500 fa-sm"></i>
                                        </a>

                                        <button type="button"
                                            data-modal-target="popup-modal-[{{ $i }}]"
                                            data-modal-toggle="popup-modal-[{{ $i }}]"
                                            class="rounded-full py-2 px-3 bg-red-50 border border-red-200 hover:bg-red-100">
                                            <i class="fa-regular fa-trash-can text-red-500 fa-sm"></i>
                                        </button>

                                        <a href="{{ route('order.progress') }}">
                                            <x-button class="py-2 px-3">Order Progress</x-button>
                                        </a>
                                    @endif
                                @else

                                    <a href="{{ route('view.order', $order->id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 hover:bg-blue-200 ml-2">
                                        <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        <x-delete-confirmation-modal :route="route('delete.order', $order->id)" title="Delete Order" description="Are you sure you want to delete order '{{ $order->id }}' ?" id="{{ $i }}"/>
                    @endforeach
                </tbody>
            </x-show-table>
        </div>
    </div>
</x-app-layout>

<script>
    const primarySort = document.getElementById('primarySort');
    const statusFilter = document.getElementById('statusFilter');
    const searchInput = document.getElementById('searchInput');
    const tbody = document.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    // Function to parse dates in "YYYY-MM-DD HH:mm:ss" format
    const parseDate = (dateString) => {
        const [datePart, timePart] = dateString.trim().split(' ');
        const [year, month, day] = datePart.split('-').map(Number);
        return new Date(year, month - 1, day);
    };

    // Function to update row colors based on the index and user role
    const updateRowColors = () => {
        rows.forEach((row, index) => {
            row.classList.remove('bg-primary-50', 'bg-purple-50'); // Remove existing colors
            // Check if the user is an admin
            if (authUserHasRole('admin')) {
                if (index % 2 === 0) row.classList.add('bg-primary-50'); // Even rows for admin
                else row.classList.add('bg-white'); // Odd rows for admin (default background)
            } else {
                if (index % 2 === 0) row.classList.add('bg-purple-50'); // Even rows for non-admin
                else row.classList.add('bg-white'); // Odd rows for non-admin
            }
        });
    };

    // Function to check if the user has the 'admin' role (example placeholder)
    const authUserHasRole = (role) => {
        return {{ auth()->user()->hasRole('admin') ? 'true' : 'false' }};  // Adjust as per your backend setup
    };

    const filterAndSortRows = () => {
        let filteredRows = [...rows];

        // Apply Status Filter
        const statusValue = statusFilter.value.toLowerCase();
        if (statusValue) {
            filteredRows = filteredRows.filter(row =>
                row.children[5].textContent.trim().toLowerCase() === statusValue
            );
        }

        // Apply Primary Sorting
        const sortValue = primarySort.value;
        if (sortValue && sortValue !== "all") {
            const columnIndex = sortValue.includes('total') ? 3 : 4; // Total or Date column
            filteredRows.sort((a, b) => {
                const valueA = sortValue.includes('date')
                    ? parseDate(a.children[columnIndex].textContent.trim())
                    : parseFloat(a.children[columnIndex].textContent) || 0;
                const valueB = sortValue.includes('date')
                    ? parseDate(b.children[columnIndex].textContent.trim())
                    : parseFloat(b.children[columnIndex].textContent) || 0;
                return sortValue.endsWith('asc') ? valueA - valueB : valueB - valueA;
            });
        }

        // Clear and re-render filtered/sorted rows
        tbody.innerHTML = '';
        filteredRows.forEach(row => tbody.appendChild(row));

        // Update the row colors after sorting/filtering
        updateRowColors();
    };

    // Event Listeners
    primarySort.addEventListener('change', filterAndSortRows);

    statusFilter.addEventListener('change', function () {
        // Call your sorting/filtering logic
        filterAndSortRows();

        // Add or remove light green background dynamically
        if (statusFilter.value === 'completed') {
            statusFilter.style.backgroundColor = '#d8ffc4'; // Light green
        } else if (statusFilter.value === 'pending') {
            statusFilter.style.backgroundColor = '#ffffc4'; // Light yellow
        } else if (statusFilter.value === 'canceled') {
                statusFilter.style.backgroundColor = '#ffcdc4'; // Light red
        } else {
            statusFilter.style.backgroundColor = ''; // Reset background to default
        }
    });

    searchInput.addEventListener('input', function (e) {
        const searchQuery = e.target.value.toLowerCase();
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = rowText.includes(searchQuery) ? '' : 'none';
        });
    });
</script>
