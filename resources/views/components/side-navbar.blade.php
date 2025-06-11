<div class="justify-start flex-col flex" style="background-color: #3E3E3E; width: 20%;">

    {{-- Sidebar Navigation --}}
    <div class="py-3 px-4 grow bg-[#1e1e1e]">

        {{-- Dashboard --}}
        <x-navbar-item href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
            <x-slot name="icon"><i class="fa-light fa-objects-column"></i></x-slot>
            <x-slot name="title">{{ __('Dashboard') }}</x-slot>
        </x-navbar-item>

        @if(auth()->user()->hasRole('user'))

            {{-- Product --}}
            <x-navbar-item href="{{ route('product') }}" :active="request()->routeIs('product')">
                <x-slot name="icon"><i class="fa-light fa-cube"></i></x-slot>
                <x-slot name="title">{{ __('Product') }}</x-slot>
            </x-navbar-item>

            {{-- Cart --}}
            <x-navbar-item href="{{ route('cart.list') }}" :active="request()->routeIs('cart.list')">
                <x-slot name="icon"><i class="fa-light fa-shopping-cart"></i></x-slot>
                <x-slot name="title">Cart</x-slot>
            </x-navbar-item>

            {{-- Order Progress --}}
            <x-navbar-item href="{{ route('order.progress') }}" :active="request()->routeIs('order.progress')">
                <x-slot name="icon"><i class="fa-light fa-clipboard-list"></i></x-slot>
                <x-slot name="title">{{ __('Order Progress') }}</x-slot>
            </x-navbar-item>

            {{-- Purchase History (new) --}}
            <x-navbar-item href="{{ route('order.history') }}" :active="request()->routeIs('order.history')">
                <x-slot name="icon"><i class="fa-light fa-clock-rotate-left"></i></x-slot>
                <x-slot name="title">{{ __('Purchase History') }}</x-slot>
            </x-navbar-item>

        @else

            {{-- Product List --}}
            <x-navbar-item href="{{ route('product.list') }}" :active="request()->routeIs('product.list')">
                <x-slot name="icon"><i class="fa-light fa-cubes"></i></x-slot>
                <x-slot name="title">{{ __('Product List') }}</x-slot>
            </x-navbar-item>

            {{-- New Order Notifications (new) --}}
            <x-navbar-item href="{{ route('order.notifications') }}" :active="request()->routeIs('order.notifications')">
                <x-slot name="icon"><i class="fa-light fa-bell"></i></x-slot>
                <x-slot name="title">{{ __('New Orders') }}</x-slot>
            </x-navbar-item>

        @endif

        {{-- Order List --}}
        <x-navbar-item href="{{ route('order.list') }}" :active="request()->routeIs('order.list')">
            <x-slot name="icon"><i class="fa-light fa-list-alt"></i></x-slot>
            <x-slot name="title">{{ __('Order List') }}</x-slot>
        </x-navbar-item>

        {{-- Payment --}}
        <x-navbar-item href="{{ route('payment.list') }}" :active="request()->routeIs('payment.list')">
            <x-slot name="icon"><i class="fa-light fa-credit-card"></i></x-slot>
            <x-slot name="title">{{ __('Payment') }}</x-slot>
        </x-navbar-item>

        {{-- Recycle Activity --}}
        <x-navbar-item href="{{ route('recycle.activity.list') }}" :active="request()->routeIs('recycle.activity.list')">
            <x-slot name="icon"><i class="fa-light fa-recycle"></i></x-slot>
            <x-slot name="title">{{ __('Recycle Activity') }}</x-slot>
        </x-navbar-item>

        {{-- Rewards --}}
        <x-navbar-item href="{{ route('reward.list') }}" :active="request()->routeIs('reward.list')">
            <x-slot name="icon"><i class="fa-light fa-gift"></i></x-slot>
            <x-slot name="title">
                {{ auth()->user()->hasRole('admin') ? __('Rewards Settings') : __('Redeem Rewards') }}
            </x-slot>
        </x-navbar-item>

        @if(auth()->user()->hasRole('admin'))
            {{-- User Management --}}
            <x-navbar-item href="{{ route('user.list') }}" :active="request()->routeIs('user.list')">
                <x-slot name="icon"><i class="fa-light fa-user"></i></x-slot>
                <x-slot name="title">{{ __('Users Management') }}</x-slot>
            </x-navbar-item>
        @endif
    </div>

    {{-- Logout Button --}}
    <div class="px-6 py-4 bg-[#1e1e1e]">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center mt-8 py-2 rounded-sm justify-center bg-red-500 text-gray-100 hover:bg-red-600 hover:text-white active:ring-red-700 active:ring-2 active:ring-offset-2 drop-shadow-[0px_0px_12px_rgba(255,150,150,0.2)]">
            <div class="text-inherit">
                <i class="fa-light fa-arrow-right-from-bracket"></i>
            </div>
            <div class="text-inherit pl-2 font-semibold text-sm">
                {{ __('Log Out') }}
            </div>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</div>
