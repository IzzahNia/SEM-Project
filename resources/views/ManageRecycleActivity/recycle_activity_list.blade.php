<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            Recycle Activity 
        </x-slot>
        <x-slot name="data">
            {{ auth()->user()->hasRole('admin') ? 
            'Admin able to create, edit, and delete recycle activity on this page.' : 
            'You can create and edit your recycle activity on this page.' 
           }}        
        </x-slot>
    </x-page-comment>

    <div class="mx-10">
        <a href="{{ route('add.recycle.activity') }}"><x-button>Add Recycle Activity</x-button></a>
    </div>

    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
        <div class="col-span-3">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">Recycle Activity Lists</p>
                <div class="flex items-center space-x-4">
                    <!-- Primary Sorting Dropdown -->
                    <select id="primarySort" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold">
                        <option value="">Sort By</option>
                        <option value="weight_asc">Weight (Low to High)</option>
                        <option value="weight_desc">Weight (High to Low)</option>
                        <option value="price_asc">Recycle Price (Low to High)</option>
                        <option value="price_desc">Recycle Price (High to Low)</option>
                        <option value="reward_asc">Reward Points (Low to High)</option>
                        <option value="reward_desc">Reward Points (High to Low)</option>
                    </select>

                    <!-- Status Filtering Dropdown -->
                    <select id="statusFilter" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-semibold">
                        <option value="">Filter by Status</option>
                        <option value="completed">Completed</option>
                        <option value="received">Received</option>
                        <option value="rejected">Rejected</option>
                    </select>

                    <!-- Search Input -->
                    <input type="text" id="searchInput" placeholder="Search" 
                    class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring">
                </div>                        
            </div>
            <x-show-table :headers="['Date', 'Plastic Category', 'Recycle Rate Price (RM)', 'Weight (KG)', 'Recycle Price (RM)','Reward Point Earned', 'Status', 'User ', 'Action']">
                <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                    @foreach ($recycleActivities as $i => $recycleActivity)
                        <tr class="flex px-8 py-2 {{ auth()->user()->hasRole('admin') ? (($loop->index % 2 == 0) ? 'bg-primary-50' : '') : (($loop->index % 2 == 0) ? 'bg-purple-50' : '') }}">
                            <td class="mx-4 py-2 text-gray text-sm font-semibold w-4">{{ $loop->iteration }}.</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $recycleActivity->recycle_datetime }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $recycleActivity->recycle_category }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $recycleActivity->recycle_rate }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $recycleActivity->recycle_weight }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $recycleActivity->recycle_price }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $recycleActivity->reward_point_earned }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ ucfirst($recycleActivity->recycle_status) }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $recycleActivity->user->name }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">
                                @if ($recycleActivity->recycle_status == 'Completed')
                                    <a href="{{ route('view.recycle.activity', $recycleActivity->id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 hover:bg-blue-200 ml-2">
                                        <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                    </a>
                                @else
                                    <a href="{{ route('edit.recycle.activity', $recycleActivity->id) }}" class="rounded-full py-2 px-3 bg-green-100 border border-green-200 hover:bg-green-200 ml-2">
                                        <i class="fa-regular fa-pen text-green-500 fa-sm"></i>
                                    </a>
                                    <button type="button" data-modal-target="popup-modal-[{{ $i }}]" data-modal-toggle="popup-modal-[{{ $i }}]" class="rounded-full py-2 px-3 bg-red-50 border border-red-200 justify-center items-center hover:bg-red-100 ml-2"><i class="fa-regular fa-trash-can text-red-500 fa-sm"></i></button>
                                @endif
                            </td>        
                        </tr>
                        <x-delete-confirmation-modal :route="route('delete.recycle.activity', $recycleActivity->id)" title="Delete Recycle Activity" description="Are you sure to delete Recycle Activity '{{ $recycleActivity->id }}' from {{ $recycleActivity->user->name }}?" id="{{ $i }}"/>
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

    const filterAndSortRows = () => {
        let filteredRows = [...rows];

        // Apply Status Filter
        const statusValue = statusFilter.value.toLowerCase();
        if (statusValue) {
            filteredRows = filteredRows.filter(row => 
                row.children[7].textContent.trim().toLowerCase() === statusValue
            );
        }

        // Apply Primary Sorting
        const sortValue = primarySort.value;
        if (sortValue) {
            const columnIndex = sortValue.includes('weight') ? 4 : 
                                sortValue.includes('price') ? 5 : 6;

            filteredRows.sort((a, b) => {
                const valueA = parseFloat(a.children[columnIndex].textContent) || 0;
                const valueB = parseFloat(b.children[columnIndex].textContent) || 0;
                return sortValue.endsWith('asc') ? valueA - valueB : valueB - valueA;
            });
        }

        // Clear and re-render filtered/sorted rows
        tbody.innerHTML = '';
        filteredRows.forEach(row => tbody.appendChild(row));
    };

    primarySort.addEventListener('change', filterAndSortRows);

    statusFilter.addEventListener('change', function () {
        // Call your sorting/filtering logic
        filterAndSortRows();

        // Add or remove light green background dynamically
        if (statusFilter.value === 'completed') {
            statusFilter.style.backgroundColor = '#d8ffc4'; // Light green
        } else if (statusFilter.value === 'received') {
            statusFilter.style.backgroundColor = '#ffffc4'; // Light yellow
        } else if (statusFilter.value === 'rejected') {
                statusFilter.style.backgroundColor = '#ffcdc4'; // Light red
        } else {
            statusFilter.style.backgroundColor = ''; // Reset background to default
        }
    });

    searchInput.addEventListener('input', (e) => {
        const searchQuery = e.target.value.toLowerCase();
        rows.forEach(row => {
            const rowText = Array.from(row.children).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = rowText.includes(searchQuery) ? '' : 'none';
        });
    });
</script>
