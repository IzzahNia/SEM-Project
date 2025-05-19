<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            User Management
        </x-slot>
        <x-slot name="data">
            Admin is able to create, edit, and delete users on this page.
        </x-slot>
    </x-page-comment>

    <div class="flex items-stretch mx-10 gap-6 w-auto">
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#f7eea0] px-6">
                    <i class="fas fa-users fa-xl text-[#ab541f]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                Total Customer
            </x-slot>
            <x-slot name="data">
                {{ $allUsersCount }}
            </x-slot>
        </x-dashboard-item>
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#ADEAAF] px-6">
                    <i class="fas fa-user-clock fa-xl text-[#365671]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                Active Customer
            </x-slot>
            <x-slot name="data">
                {{ $activeUsersPast7Days }}
            </x-slot>
        </x-dashboard-item>
        <x-dashboard-item>
            <x-slot name="icon">
                <div class="flex items-center rounded-md bg-[#e2d4ed] px-6">
                    <i class="fas fa-user-shield fa-xl text-[#6f518c]"></i>
                </div>
            </x-slot>
            <x-slot name="title">
                Admin
            </x-slot>
            <x-slot name="data">
                {{ $allAdminCount }}
            </x-slot>
        </x-dashboard-item>
    </div>

    <div class="mx-10 mt-6">
        <a href="{{ route('add.user') }}"><x-button>Add User</x-button></a>
    </div>

    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
        <div class="col-span-3">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">User Lists</p>
                <div class="flex items-center space-x-4">
                    <select id="roleFilter" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    <input type="text" id="searchInput" placeholder="Search"
                           class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <x-show-table :headers="['Name', 'User ID', 'Role', 'Email', 'Action']">
                <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                    @foreach ($users as $i => $user)
                        <tr class="flex px-8 py-2 {{ $loop->index % 2 == 0 ? 'bg-primary-50' : '' }}" data-role="{{ $user->role }}">
                            <td class="mx-4 py-2 text-gray text-sm font-semibold w-4">{{ $loop->iteration }}.</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $user->name }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $user->id }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $user->role }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $user->email }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">
                                <a href="{{ route('edit.user', $user->id) }}" class="rounded-full py-2 px-3 bg-green-100 border border-green-200 justify-center items-center hover:bg-green-200 ml-2">
                                    <i class="fa-regular fa-pen text-green-500 fa-sm"></i>
                                </a>
                                <button type="button" data-modal-target="popup-modal-[{{ $i }}]" data-modal-toggle="popup-modal-[{{ $i }}]" class="rounded-full py-2 px-3 bg-red-50 border border-red-200 justify-center items-center hover:bg-red-100 ml-2"><i class="fa-regular fa-trash-can text-red-500 fa-sm"></i></button>
                            </td>
                        </tr>
                        <x-delete-confirmation-modal :route="route('delete.user', $user->id)" title="Delete User" description="Are you sure to delete '{{ $user->name }}' ?" id="{{ $i }}"/>
                    @endforeach
                </tbody>
            </x-show-table>
        </div>
    </div>
</x-app-layout>

<script>
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const rows = document.querySelectorAll('tbody tr');

    // Filter by search
    searchInput.addEventListener('input', function (e) {
        const searchQuery = e.target.value.toLowerCase();
        rows.forEach(row => {
            const rowText = Array.from(row.querySelectorAll('td')).map(cell => cell.textContent.toLowerCase()).join(' ');
            row.style.display = rowText.includes(searchQuery) ? '' : 'none';
        });
    });

    // Filter by role
    roleFilter.addEventListener('change', function () {
        const selectedRole = this.value;
        rows.forEach(row => {
            const role = row.getAttribute('data-role');
            if (!selectedRole || role === selectedRole) {
                row.style.display = ''; // Show the row
            } else {
                row.style.display = 'none'; // Hide the row
            }
        });
    });
</script>
