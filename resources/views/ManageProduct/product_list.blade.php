<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            {{ auth()->user()->hasRole('admin') ? 
            'Product Management' : 
            'Product Page' 
           }}   
        </x-slot>
        <x-slot name="data">
            {{ auth()->user()->hasRole('admin') ? 
            'This product list is created for sales. Admin can manage the products by updating the status.' : 
            'You can view the selling products list on this page.' 
           }}        
        </x-slot>
    </x-page-comment>

    @if(auth()->user()->hasRole('admin'))
    <div class="mx-10">
        <a href="{{ route('add.product') }}"><x-button>Add Product</x-button></a>
    </div>
    @endif 

    <div class="grid grid-cols-5 gap-6 mx-10 mt-6">
        <!-- Sidebar -->
        {{-- <div class="col-span-1 bg-gray-100 p-4 rounded-lg">
            <h3 class="font-bold text-lg mb-4">Categories</h3>
            <ul>
                <li><a href="{{ route('product.list') }}" class="block py-2 px-4 hover:bg-gray-200 rounded">All Products</a></li>
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('product.list', ['category' => $category->product_category]) }}" 
                           class="block py-2 px-4 hover:bg-gray-200 rounded">
                            {{ $category->product_category }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div> --}}
        <div class="col-span-1 bg-gray-100 p-4 rounded-md">
            <h3 class="font-bold text-lg mb-4 text-center">Categories</h3>
            <ul>
                <!-- All Products Link -->
                <li>
                    <a href="{{ route('product.list') }}" 
                       class="block py-2 px-4 rounded hover:text-primary-800 font-semibold 
                       {{ request('category') ? '' : 'border border-primary-600 bg-white text-primary-600 font-bold' }}">
                       All Products
                    </a>
                </li>
                
                <!-- Category Links -->
                @foreach ($categories as $category)
                    <li>
                        <a href="{{ route('product.list', ['category' => $category->product_category]) }}" 
                           class="block py-2 px-4 rounded hover:text-primary-800 font-semibold 
                           {{ request('category') == $category->product_category ? 'border border-primary-600 bg-white text-primary-600 font-bold' : '' }}">
                           {{ $category->product_category }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div class="col-span-4">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">Product Lists</p>
                <div class="flex items-center space-x-4">
                    <input type="text" id="searchInput" placeholder="Search" 
                    class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring {{ auth()->user()->hasRole('admin') ? 'focus:ring-primary-500 focus:border-primary-500' : 'focus:ring-purple-500 focus:border-purple-500' }}">
                    <select id="sortSelect" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="">Sort By</option>
                        <option value="selling_price_asc">Selling Price (Low to High)</option>
                        <option value="selling_price_desc">Selling Price (High to Low)</option>
                        <option value="quantity_asc">Quantity (Low to High)</option>
                        <option value="quantity_desc">Quantity (High to Low)</option>
                        <option value="purchase_price_asc">Purchase Price (Low to High)</option>
                        <option value="purchase_price_desc">Purchase Price (High to Low)</option>
                    </select>
                </div>               
            </div>
            <x-show-table :headers="['Product', 'Serial', 'Sales Price (RM)', 'Purchase Price (RM)', 'Quantity', 'Status', 'Image','Action']">
                <tbody class="flex flex-col overflow-y-auto w-full" style="height: 40vh;">
                    @foreach ($products->filter(function ($product) {return auth()->user()->hasRole('admin') || $product->product_quantity > 0;}) as $i => $product)
                        <tr class="flex px-8 py-2 {{ auth()->user()->hasRole('admin') ? (($loop->index % 2 == 0) ? 'bg-primary-50' : '') : (($loop->index % 2 == 0) ? 'bg-purple-50' : '') }}">
                            <td class="mx-4 py-2 text-gray text-sm font-semibold w-4">{{ $loop->iteration }}.</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $product->product_name }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $product->product_serial_number }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $product->product_selling_price }}</td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">{{ $product->product_purchase_price }}</td>
                            @if(auth()->user()->hasRole('admin'))
                            <td class="py-2 text-sm font-bold text-left w-1/3 
                                {{ $product->product_quantity == 0 ? 'text-red-700' : ($product->product_quantity <= 10 ? 'text-yellow-500' : 'text-gray-700') }}">
                                {{ $product->product_quantity }}
                            </td>
                            <td class="py-2 text-sm font-semibold text-left w-1/3 
                                {{ $product->product_quantity == 0 ? 'text-red-700' : ($product->product_quantity <= 10 ? 'text-yellow-500' : 'text-gray-700') }}">
                                {{ $product->product_status }}
                            </td>
                            @else
                            <td class="py-2 text-sm font-semibold text-left w-1/3">{{ $product->product_quantity }}</td>
                            <td class="py-2 text-sm font-semibold text-left w-1/3">{{ $product->product_status }}</td>
                            @endif
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">
                                @if($product->product_image)
                                    <img src="{{ asset('images/products/' . $product->product_image) }}" class="w-12 h-12 object-cover rounded">
                                @else
                                    <span>No Image</span>
                                @endif
                            </td>
                            <td class="py-2 text-gray text-sm font-semibold text-left w-1/3">
                                <a href="{{ route('view.product', $product->id) }}" class="rounded-full py-2 px-3 bg-blue-100 border border-blue-200 justify-center items-center hover:bg-blue-200 ml-2">
                                    <i class="fa-regular fa-eye text-blue-500 fa-sm"></i>
                                </a>
                                @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('edit.product', $product->id) }}" class="rounded-full py-2 px-3 bg-green-100 border border-green-200 justify-center items-center hover:bg-green-200 ml-2">
                                    <i class="fa-regular fa-pen text-green-500 fa-sm"></i>
                                </a>
                                <button type="button" data-modal-target="popup-modal-[{{ $i }}]" data-modal-toggle="popup-modal-[{{ $i }}]" class="rounded-full py-2 px-3 bg-red-50 border border-red-200 justify-center items-center hover:bg-red-100 ml-2"><i class="fa-regular fa-trash-can text-red-500 fa-sm"></i></button>
                                @endif 
                            </td>
                        </tr>
                        <x-delete-confirmation-modal :route="route('delete.product', $product->id)" title="Delete Product" description="Are you sure to delete '{{ $product->product_name }}' ?" id="{{ $i }}"/>
                    @endforeach
                </tbody>
            </x-show-table>
        </div>
    </div>
</x-app-layout>

<script>
    const sortSelect = document.getElementById('sortSelect');
    const searchInput = document.getElementById('searchInput');
    const tbody = document.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    // Helper function to extract a numeric value from a specific column
    const getCellValue = (row, columnIndex) => {
        const cell = row.children[columnIndex];
        return cell ? parseFloat(cell.textContent.trim()) || 0 : 0; // Default to 0 if invalid
    };

    // Map of sortable columns with their indices
    const columns = {
        selling_price: 3,  // 4th column (index 3) for Selling Price
        quantity: 5,       // 6th column (index 5) for Quantity
        purchase_price: 4, // 5th column (index 4) for Purchase Price
    };

    // Function to update alternating row colors
    const updateRowColors = () => {
        rows.forEach((row, index) => {
            row.classList.remove('bg-primary-50', 'bg-purple-50'); // Remove existing colors
            if (authUserHasRole('admin')) {
                if (index % 2 === 0) row.classList.add('bg-primary-50');
            } else {
                if (index % 2 === 0) row.classList.add('bg-purple-50');
            }
        });
    };

    // Sorting functionality
    sortSelect.addEventListener('change', function () {
        const sortBy = this.value; // Get the selected sorting option
        let sortedRows;

        if (sortBy.includes('selling_price')) {
            const columnIndex = columns['selling_price'];
            sortedRows = rows.sort((a, b) => {
                const aValue = getCellValue(a, columnIndex);
                const bValue = getCellValue(b, columnIndex);
                return sortBy === 'selling_price_asc' ? aValue - bValue : bValue - aValue;
            });
        } else if (sortBy.includes('quantity')) {
            const columnIndex = columns['quantity'];
            sortedRows = rows.sort((a, b) => {
                const aValue = getCellValue(a, columnIndex);
                const bValue = getCellValue(b, columnIndex);
                return sortBy === 'quantity_asc' ? aValue - bValue : bValue - aValue;
            });
        } else if (sortBy.includes('purchase_price')) {
            const columnIndex = columns['purchase_price'];
            sortedRows = rows.sort((a, b) => {
                const aValue = getCellValue(a, columnIndex);
                const bValue = getCellValue(b, columnIndex);
                return sortBy === 'purchase_price_asc' ? aValue - bValue : bValue - aValue;
            });
        }

        // Append sorted rows back to the table body
        if (sortedRows) sortedRows.forEach(row => tbody.appendChild(row));

        // Reapply alternating row colors
        updateRowColors();
    });

    // Searching functionality
    searchInput.addEventListener('input', function (e) {
        const searchQuery = e.target.value.toLowerCase();

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const rowText = Array.from(cells).map(cell => cell.textContent.toLowerCase()).join(' ');

            row.style.display = rowText.includes(searchQuery) ? '' : 'none';
        });

        // Reapply alternating row colors (in case rows are hidden)
        updateRowColors();
    });

    // Helper function to determine if the user is an admin
    const authUserHasRole = (role) => {
        return "{{ auth()->user()->hasRole('admin') ? 'admin' : '' }}" === role;
    };

    // Initial setup: Apply alternating row colors
    updateRowColors();
</script>
<style>

</style>