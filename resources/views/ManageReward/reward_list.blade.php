<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            {{ auth()->user()->hasRole('admin') ? 
            'Rewards Settings' : 
            'Redeem Rewards' 
            }}  
            </x-slot>
        <x-slot name="data">
            {{ auth()->user()->hasRole('admin') ? 
            'Admin is able to create, edit, delete, and verify reward codes from users on this page.' : 
            'You can redeem rewards with your reward points!' 
            }}        
        </x-slot>
    </x-page-comment>

    <div class="flex mx-10 w-auto">
        @if(auth()->user()->hasRole('user'))
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#fcffd4] px-6">
                    <i class="fas fa-star fa-xl text-[#f7d01f]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                Current Reward Points
            </x-slot>
            <x-slot name="data">
                {{ $user->current_reward_points }}
            </x-slot>
        </x-dashboard-item>
        @endif

        <a href="{{ route('my.reward.list') }}" class="flex px-4 py-4 bg-white text-gray rounded-md mx-8 w-1/3 drop-shadow-[0px_0px_24px_rgba(120,120,120,0.15)]">
            <div class="flex items-center rounded-md bg-[#faebeb] px-6">
                <i class="fas fa-gift fa-xl text-[#e63d3d]"></i>
            </div>
            <div class="flex flex-col mt-2 mx-2 grow justify-start">
                <div class="flex justify-center">
                    <p class="text-start text-lg text-black font-bold">                
                        {{ Auth::user()->role === 'admin' ? 'Total Redeem Rewards' : 'My Rewards' }}
                    </p>
                </div>
                <!-- Line of words (below "My Rewards") -->
                <div class="flex justify-center">
                    @if(auth()->user()->hasRole('user'))
                    <p class="text-start font-bold text-3xl">{{ $availableRewardsCount }}</p>
                    @else
                    <p class="text-start font-bold text-3xl">{{ $totalRedeemedRewardsCount }}</p>
                    @endif
                </div>
                <div class="flex justify-center">
                    <p class="text-start text-md text-gray-600">Click Me!</p>
                </div>
            </div>
        </a>        

        @php
            // Check if any reward quantity is less than 10
            $lowStock = $rewards->some(fn($reward) => $reward->reward_quantity < 10);
        @endphp

        <div class="flex px-4 py-4 bg-white text-gray rounded-md w-1/3 drop-shadow-[0px_0px_24px_rgba(120,120,120,0.15)]">
            <div class="flex items-center rounded-md px-6 w-full {{ $lowStock ? 'bg-[#faebeb]' : 'bg-blue-100' }}">
                <div class="flex flex-col mt-2 mx-2 grow justify-start">
                    <div class="flex justify-center">
                        <p class="text-start text-bm text-black font-bold">
                            {{ Auth::user()->role === 'admin' ? 'Rewards Threshold' : 'Rewards Stock' }}
                        </p>
                    </div>
                    <div class="flex justify-center rounded-md px-1">
                        <p class="text-start text-lg font-semibold {{ $lowStock ? 'text-red-500' : 'text-blue-500' }}">
                            @if($lowStock)
                                {{ Auth::user()->role === 'admin' ? 'Reward Stock is Low, Required Restock!' : 'Limited Stock left, Redeem Quickly!' }}
                                @else
                                {{ Auth::user()->role === 'admin' ? 'Reward Stock is Normal' : 'Reward stock is available' }}
                            @endif
                        </p>
                    </div>                    
                </div>
            </div>
        </div>

    </div>
    
    @if(auth()->user()->hasRole('admin'))
    <div class="mx-10 mt-8">
        <a href="{{ route('add.reward') }}"><x-button>Add Reward</x-button></a>
        <a href="{{ route('redeem.reward.code') }}" style="margin-left: 20px"><x-redeem-button>Reward Redemption</x-redeem-button></a>
    </div>
    @endif

    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
        <div class="col-span-3">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md ">Reward Lists</p>
                <div class="flex items-center space-x-4">
                    <!-- Sorting Dropdown -->
                    <select id="primarySort" class="py-2 px-4 border border-gray-300 rounded-md text-sm font-semibold">
                        <option value="">Sort By</option>
                        <option value="points_asc">Points Required (Low to High)</option>
                        <option value="points_desc">Points Required (High to Low)</option>
                        <option value="date_asc">End Date (Oldest First)</option>
                        <option value="date_desc">End Date (Newest First)</option>
                        <option value="stock_asc">Stock (Low to High)</option>
                        <option value="stock_desc">Stock (High to Low)</option>
                    </select>
                    @if(auth()->user()->hasRole('admin'))
                    <!-- Status Filtering Dropdown -->
                    <select id="statusFilter" class="py-2 px-4 border border-gray-300 rounded-md text-sm font-semibold">
                        <option value="">Filter by Status</option>
                        <option value="available">Available</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                    @endif
                    <!-- Search Input -->
                    <input type="text" id="searchInput" placeholder="Search" 
                    class="py-2 px-4 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring {{ auth()->user()->hasRole('admin') ? 'focus:ring-primary-500 focus:border-primary-500' : 'focus:ring-purple-500 focus:border-purple-500' }}">
                </div>            
            </div>
            <x-show-table :headers="['Rewards', 'Reward Points Required', 'End Date', 'Status', 'Stock', 'Image', 'Action']">
                <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                    @foreach ($rewards->filter(function ($reward) {
                        return auth()->user()->hasRole('admin') || $reward->reward_quantity > 0;
                    }) as $i => $reward)
                        <tr class="flex px-8 py-2 {{ auth()->user()->hasRole('admin') ? (($loop->index % 2 == 0) ? 'bg-primary-50' : '') : (($loop->index % 2 == 0) ? 'bg-purple-50' : '') }}">
                            <td class="mx-4 py-2 text-gray text-sm font-semibold w-4">{{ $loop->iteration }}.</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $reward->reward_name }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $reward->reward_point_required }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $reward->reward_duration_date }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ ucfirst($reward->reward_status) }}</td>
                            <td class="py-2 text-sm font-semibold text-left w-1/3 {{ $reward->reward_quantity < 10 ? 'text-red-700' : 'text-gray' }}">
                                {{ $reward->reward_quantity }}
                            </td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">
                                @if($reward->reward_image)
                                    <img src="{{ asset('images/rewards/' . $reward->reward_image) }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">
                                <a href="{{ route('view.reward', $reward->id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 justify-center items-center hover:bg-blue-200 ml-2">
                                    <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                </a>
                                @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('edit.reward', $reward->id) }}" class="rounded-full py-2 px-3 bg-green-100 border border-green-200 justify-center items-center hover:bg-green-200 ml-2">
                                    <i class="fa-regular fa-pen text-green-500 fa-sm"></i>
                                </a>
                                <button type="button" data-modal-target="popup-modal-[{{ $i }}]" data-modal-toggle="popup-modal-[{{ $i }}]" class="rounded-full py-2 px-3 bg-red-50 border border-red-200 justify-center items-center hover:bg-red-100 ml-2"><i class="fa-regular fa-trash-can text-red-500 fa-sm"></i></button>
                                @endif
                            </td>  
                        </tr>
                        <x-delete-confirmation-modal :route="route('delete.reward', $reward->id)" title="Delete Reward" description="Are you sure to delete '{{ $reward->reward_name }}' ?" id="{{ $i }}"/>
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

    const parseDate = (dateString) => new Date(dateString.trim());

    const filterAndSortRows = () => {
        let filteredRows = [...rows];

        // Filter by Status
        const statusValue = statusFilter.value.toLowerCase();
        if (statusValue) {
            filteredRows = filteredRows.filter(row => 
                row.children[4].textContent.trim().toLowerCase() === statusValue
            );
        }

        // Apply Sorting
        const sortValue = primarySort.value;
        if (sortValue) {
            const columnIndex = sortValue.includes('points') ? 2 : 
                                sortValue.includes('date') ? 3 : 5;

            filteredRows.sort((a, b) => {
                const valueA = columnIndex === 3 ? parseDate(a.children[columnIndex].textContent) : parseFloat(a.children[columnIndex].textContent);
                const valueB = columnIndex === 3 ? parseDate(b.children[columnIndex].textContent) : parseFloat(b.children[columnIndex].textContent);
                return sortValue.endsWith('asc') ? valueA - valueB : valueB - valueA;
            });
        }

        tbody.innerHTML = '';
        filteredRows.forEach(row => tbody.appendChild(row));
    };

    primarySort.addEventListener('change', filterAndSortRows);

    statusFilter.addEventListener('change', function () {
        // Call your sorting/filtering logic
        filterAndSortRows();

        // Add or remove light green background dynamically
        if (statusFilter.value === 'available') {
            statusFilter.style.backgroundColor = '#d8ffc4'; // Light green
        } else if (statusFilter.value === 'unavailable') {
            statusFilter.style.backgroundColor = '#ffffc4'; // Light yellow
        } else {
            statusFilter.style.backgroundColor = ''; // Reset background to default
        }
    });

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        rows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(query) ? '' : 'none';
        });
    });
</script>