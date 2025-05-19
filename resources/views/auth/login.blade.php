<x-guest-layout>
    <!-- Session Status -->
    
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Title of the form -->
        <h2 class="text-center text-xl mb-6 font-bold">Login</h2>

        <!-- Flexbox layout for logo and form -->
        <div class="flex items-center justify-center mb-6 w-full space-x-6">
            <!-- Logo Section -->
			<div class="flex flex-col items-center space-y-4">
                <!-- HLH Logo -->
                <img src="{{ asset('img/HLH-logo.png') }}" alt="HLH Logo" style="width: 200px; height: auto;">

                <!-- Plasticware Logo -->
                <img src="{{ asset('img/plasticware_logo.png') }}" alt="Plasticware Logo" style="width: 300px; height: auto;">
            </div>

            <!-- Form Section -->
            <div class="w-2/3 p-4" style="width: 300px;">
                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-[#0097A1]" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="Eg: wen@gmail.com" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Role Selection -->
                <div class="mb-4">
                    <x-input-label for="role" :value="__('Role')" />
                    <select id="role" name="role" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="" disabled selected>{{ __('Select a role') }}</option>
                        <option value="admin">{{ __('Admin') }}</option>
                        <option value="user">{{ __('User') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Forgot Password Link -->
                <div class="flex justify-end mb-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-red-600 hover:text-red-800" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <!-- Forgot Password Link -->
                <div class="flex justify-end mb-4">
                    @if (Route::has('register'))
                        <a class="underline text-sm text-blue-600 hover:text-blue-800" href="{{ route('register') }}">
                            {{ __('Register New Account') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex justify-center">
                    <x-primary-button style="background-color: #9100a1; hover:bg: #007B8D; color: white;" class="w-full py-2 rounded-md">
                        {{ __('Login') }}
                    </x-primary-button>
                </div>
            </div>
        </div>
    </form>
</x-guest-layout>
