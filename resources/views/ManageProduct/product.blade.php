<x-app-layout>
    <x-page-comment>
        <x-slot name="title">
            Product List 
        </x-slot>
        <x-slot name="data">
            You can view the selling products and add them to the cart.     
        </x-slot>
    </x-page-comment>

    <div class="mx-10">

    <!-- 🔍 Search & Filter Form -->
    <form method="GET" action="{{ route('product') }}" class="flex flex-wrap gap-4 mb-6 items-center bg-white p-4 rounded shadow">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search by name" 
            class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/4"
        />

        <select name="category" class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/5">
            <option value="">All Categories</option>
            @foreach ($productsByCategory as $category => $products)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        <select name="status" class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/5">
            <option value="">All Status</option>
            <option value="Available" {{ request('status') == 'Available' ? 'selected' : '' }}>Available</option>
            <option value="Low Stock" {{ request('status') == 'Low Stock' ? 'selected' : '' }}>Low Stock</option>
            <option value="Out of Stock" {{ request('status') == 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
        </select>

        <div class="w-full md:w-auto">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Search
            </button>
        </div>
    </form>

        <!-- 🗂️ Tabs -->
        <ul class="flex border-b">
            <li class="mr-2">
                <button 
                    data-category="all" 
                    class="category-tab inline-block px-4 py-2 font-semibold text-primary-600 hover:text-primary-800 active">
                    All Categories
                </button>
            </li>
            @foreach ($productsByCategory as $category => $products)
                <li class="mr-2">
                    <button 
                        data-category="{{ Str::slug($category) }}" 
                        class="category-tab inline-block px-4 py-2 font-semibold text-blue-600 hover:text-blue-800">
                        {{ $category }}
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- 📦 Product Lists by Tab -->
        <div class="mt-4">
            <div class="category-content all">
                <h3 class="font-bold text-lg mb-2">All Products</h3>
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($productsByCategory->flatten() as $i => $product)
                        @if($product->product_quantity >= 1)
                            <div class="p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 relative">
                                <a href="{{ route('view.product', $product->id) }}" class="block">
                                    @if($product->product_image)
                                        <img 
                                            src="{{ asset('images/products/' . $product->product_image) }}" 
                                            class="w-full h-32 object-contain mb-2" 
                                            alt="{{ $product->product_name }}">
                                    @else
                                        <p>No Image Available</p>
                                    @endif
                                    <h4 class="text-m font-semibold">{{ $product->product_name }}</h4>
                                    <p class="font-semibold text-gray-600">RM {{ $product->product_selling_price }}</p>
                                </a>
                                <button type="button" data-modal-target="add-to-cart-modal-[{{ $i }}]" data-modal-toggle="add-to-cart-modal-[{{ $i }}]" class="absolute bottom-2 right-2 rounded-full py-2 px-3 bg-green-50 border border-green-200 justify-center items-center hover:bg-green-100 ml-2">
                                    <i class="fa-regular fa-cart-plus text-black fa-sm"></i>
                                </button>
                                <x-add-to-cart-modal :route="route('add.cart', $product->id)" 
                                    title="Add to Cart" image="{{ $product->product_image }}" 
                                    description="{{ $product->product_name }}" 
                                    id="{{ $i }}"
                                    productID="{{ $product->id }}"
                                    maxQuantity="{{ $product->product_quantity }}"/>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            @foreach ($productsByCategory as $category => $products)
                <div class="category-content {{ Str::slug($category) }} hidden">
                    <h3 class="font-bold text-lg mb-2">{{ $category }} Products</h3>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach ($products as $i => $product)
                            @if($product->product_quantity >= 1)
                                <div class="p-4 bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 relative">
                                    <a href="{{ route('view.product', $product->id) }}" class="block">
                                        @if($product->product_image)
                                            <img 
                                                src="{{ asset('images/products/' . $product->product_image) }}" 
                                                class="w-full h-32 object-contain mb-2" 
                                                alt="{{ $product->product_name }}">
                                        @else
                                            <p>No Image Available</p>
                                        @endif
                                        <h4 class="text-m font-semibold">{{ $product->product_name }}</h4>
                                        <p class="font-semibold text-gray-600">RM {{ $product->product_selling_price }}</p>
                                    </a>
                                    <button type="button" data-modal-target="add-to-cart-modal-[{{ Str::slug($category) }}-{{ $i }}]" data-modal-toggle="add-to-cart-modal-[{{ Str::slug($category) }}-{{ $i }}]" class="absolute bottom-2 right-2 rounded-full py-2 px-3 bg-green-50 border border-green-200 justify-center items-center hover:bg-green-100 ml-2">
                                        <i class="fa-regular fa-cart-plus text-black fa-sm"></i>
                                    </button>
                                    <x-add-to-cart-modal :route="route('add.cart', $product->id)" 
                                        title="Add to Cart" image="{{ $product->product_image }}" 
                                        description="{{ $product->product_name }}" 
                                        id="{{ Str::slug($category) }}-{{ $i }}"
                                        productID="{{ $product->id }}"
                                        maxQuantity="{{ $product->product_quantity }}"/>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>


<script>
    // JavaScript to toggle active class on category tabs
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            // Remove the 'active' class from all tabs
            document.querySelectorAll('.category-tab').forEach(tab => tab.classList.remove('active'));
            
            // Add the 'active' class to the clicked tab
            this.classList.add('active');

            // Show the corresponding category
            const selectedCategory = this.getAttribute('data-category');
            document.querySelectorAll('.category-content').forEach(content => {
                content.classList.add('hidden'); // Hide all categories
            });
            if (selectedCategory === 'all') {
                document.querySelector('.category-content.all').classList.remove('hidden');
            } else {
                document.querySelector(`.category-content.${selectedCategory}`).classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            const productPrice = this.getAttribute('data-product-price');

            const modal = document.querySelector(`#cart-popup-modal-${productId}`);
            if (modal) {
                modal.querySelector('.product-name').textContent = productName;
                modal.querySelector('.product-price').textContent = productPrice;
                modal.classList.remove('hidden');
            }
        });
    });

    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', function () {
            this.closest('.modal').classList.add('hidden');
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const incrementBtns = document.querySelectorAll('.increment-btn');
        const decrementBtns = document.querySelectorAll('.decrement-btn');

        // Function to handle incrementing quantity
        incrementBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const quantityInput = document.querySelector(`input[data-id="${this.dataset.id}"]`);
                let currentQuantity = parseInt(quantityInput.value);
                const maxQuantity = parseInt(quantityInput.getAttribute('max')); // Get the max value

                if (currentQuantity < maxQuantity) {
                    quantityInput.value = currentQuantity + 1;
                } else {
                    alert(`You can only add up to ${maxQuantity} items.`);
                }

                // Update the hidden quantity input
                const hiddenQuantityInput = document.querySelector(`#hidden-quantity-${this.dataset.id}`);
                hiddenQuantityInput.value = quantityInput.value;
            });
        });

        // Function to handle decrementing quantity
        decrementBtns.forEach((btn) => {
            btn.addEventListener('click', function () {
                const quantityInput = document.querySelector(`input[data-id="${this.dataset.id}"]`);
                let currentQuantity = parseInt(quantityInput.value);

                if (currentQuantity > 1) {
                    quantityInput.value = currentQuantity - 1;
                }

                // Update the hidden quantity input
                const hiddenQuantityInput = document.querySelector(`#hidden-quantity-${this.dataset.id}`);
                hiddenQuantityInput.value = quantityInput.value;
            });
        });

        // Handling the input event for the quantity input field
        document.querySelectorAll('input[type="number"]').forEach((input) => {
            input.addEventListener('input', function () {
                const maxQuantity = parseInt(this.getAttribute('max'));
                let value = parseInt(this.value);

                // Ensure value stays within allowed range
                if (value > maxQuantity) {
                    alert(`You can only add up to ${maxQuantity} items.`);
                    this.value = maxQuantity;
                } else if (value < 1) {
                    this.value = 1;
                }

                // Update the hidden quantity input
                const hiddenQuantityInput = document.querySelector(`#hidden-quantity-${this.dataset.id}`);
                if (hiddenQuantityInput) {
                    hiddenQuantityInput.value = this.value;
                }
            });
        });
    });

    document.querySelector('#addToCartForm').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent the default form submission

        const formData = new FormData(this);

        // Use fetch to send the data to the controller
        fetch(this.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())  // Get the response in JSON format
        .then(data => {
            if (data.success) {
                alert(data.message);  // Show the success message from the controller
            } else {
                alert('There was an error adding the product to the cart.');
            }
        })
    });
</script>

<style>
    /* Highlight active tab with a bottom border and bold text */
    .category-tab.active {
        border-bottom: 2px solid #4F46E5; /* Primary color for the border */
        font-weight: bold;
        color: #382fe8; /* Matching color for active text */
    }
</style>