@props(['route', 'title', 'description', 'image', 'id', 'productID', 'maxQuantity'])

<div id="add-to-cart-modal-[{{ $id }}]" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="absolute top-3 left-6 font-bold text-2xl text-gray text-left">{{ $title }} </div>
            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="add-to-cart-modal-[{{ $id }}]">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
            <div class="pt-12 p-4">
                <hr/>
                <div class="flex justify-center mt-2">
                    <div class="flex h-40 w-40 bg-red-50 rounded-full my-2 items-center justify-center">
                            <img 
                                src="{{ asset('images/products/' . $image) }}" >
                        </div>
                </div>
                <h3 class="mb-5 text-md text-center font-semibold text-gray-800 dark:text-gray-800">
                    How many '<span style="color: #05785f; font-weight: bold;">{{ $description }}</span>' would you like to add?
                </h3>                
                <!-- Quantity Controls -->
                <div class="flex justify-center items-center space-x-4 mb-4">
                    <button type="button" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md decrement-btn" data-id="{{ $id }}">-</button>
                    <input 
                        type="number" 
                        data-id="{{ $id }}" 
                        name="quantity" 
                        min="1" 
                        max="{{ $maxQuantity }}"
                        value="1" 
                        class="w-16 text-center border rounded-md focus:ring-2 focus:ring-blue-500">
                    <button type="button" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md increment-btn" data-id="{{ $id }}">+</button>
                </div>
                
                <div class="w-full justify-end items-center flex">
                    <button data-modal-hide="add-to-cart-modal-[{{ $id }}]" type="button" class="text-gray-500 bg-white mr-2 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-md border border-gray-200 text-xs font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">CANCEL</button>
                    <form id="addToCartForm" action="{{ $route }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $productID }}">
                        <input type="hidden" name="quantity" id="hidden-quantity-{{ $id }}" value="1">
                        <button data-modal-hide="add-to-cart-modal-[{{ $id }}]" type="submit" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-md text-xs inline-flex items-center px-5 py-2.5 text-center">
                            ADD TO CART
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

