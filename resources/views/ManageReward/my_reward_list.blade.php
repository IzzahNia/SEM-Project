<x-app-layout>
    <div class="flex items-stretch mx-10 gap-6 w-auto">
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#e3fdff] px-6">
                    <i class="fas fa-gift fa-xl text-[#3cb0d1]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                {{ Auth::user()->role === 'admin' ? 'Total Reward Redeemed' : 'Total Rewards' }}
            </x-slot>
            <x-slot name="data">
                {{ $totalRewardsCount }}
            </x-slot>
        </x-dashboard-item>

        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#faebeb] px-6">
                    <i class="fas fa-gift fa-xl text-[#e63d3d]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                {{ Auth::user()->role === 'admin' ? 'Pending Rewards' : 'Available Rewards' }}
            </x-slot>
            <x-slot name="data">
                {{ $availableRewardsCount }}
            </x-slot>
        </x-dashboard-item>
    </div>

    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
        <div class="col-span-3">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">My Reward Lists</p>
                <div>
                    <input type="text" id="searchInput" placeholder="Search" 
                    class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring {{ auth()->user()->hasRole('admin') ? 'focus:ring-primary-500 focus:border-primary-500' : 'focus:ring-purple-500 focus:border-purple-500' }}">
                </div>     
            </div>
            @if(auth()->user()->hasRole('admin'))
                <x-show-table :headers="['Rewards', 'Expired Date', 'Status', 'Redeemed Date', 'User', 'Action']">
                    <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                        @foreach ($redeemRewards as $i => $redeemReward)
                            <tr class="flex px-8 py-2 {{ ($loop->index % 2 == 0) ? 'bg-primary-50' : '' }}">
                                <td class="mx-4 py-2 text-gray text-sm font-semibold w-4">{{ $loop->iteration }}.</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $redeemReward->reward->reward_name }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $redeemReward->code_expired_date }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $redeemReward->redeem_code_status }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $redeemReward->code_redeemed_date ?? '-' }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">{{ $redeemReward->user->name }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/6">
                                    <a href="{{ route('view.my.reward', $redeemReward->id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 justify-center items-center hover:bg-blue-200 ml-2">
                                        <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                    </a>
                                </td>  
                            </tr>
                        @endforeach
                    </tbody>
                </x-show-table>
            @else
                <x-show-table :headers="['Rewards', 'Expired Date', 'Status', 'Redeemed Date', 'Action']">
                    <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                        @foreach ($redeemRewards as $i => $redeemReward)
                            <tr class="flex px-8 py-2 {{ ($loop->index % 2 == 0) ? 'bg-purple-50' : '' }}">
                                <td class="mx-4 py-2 text-gray text-sm font-semibold w-4">{{ $loop->iteration }}.</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/4">{{ $redeemReward->reward->reward_name }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/4">{{ $redeemReward->code_expired_date }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/4">{{ $redeemReward->redeem_code_status }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/4">{{ $redeemReward->code_redeemed_date ?? '-' }}</td>
                                <td class="py-2 text-gray text-sm font-semibold text-left w-1/4">
                                    <a href="{{ route('view.my.reward', $redeemReward->id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 justify-center items-center hover:bg-blue-200 ml-2">
                                        <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                    </a>
                                </td>  
                            </tr>
                        @endforeach
                    </tbody>
                </x-show-table>
            @endif
        </div>
    </div>
    <div class="flex justify-center items-center">
        <a href="{{ route('reward.list') }}"><x-secondary-button class="mr-2">BACK</x-secondary-button></a>
    </div>
</x-app-layout>
<script>
    document.getElementById('searchInput').addEventListener('input', function (e) {
        const searchQuery = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');
            if (rowText.includes(searchQuery)) {
                row.style.display = ''; // Show the row
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });
    });
</script>