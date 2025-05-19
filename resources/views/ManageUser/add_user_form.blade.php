<x-app-layout>
    <div class="flex flex-col items-center mx-10 my-6">
        <!-- Title Section -->
        <div class="w-1/2">
            <p class="font-bold text-lg text-center">Add User</p>
        </div>
    </div>
    <div class="grid mx-auto my-6 rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] w-1/2">
        <div class="mt-2 mb-4 mx-6">
            <form action="{{ route('create.user') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
                @csrf
                <div class="flex flex-col space-y-4 mt-4 w-full">
                    <!-- User Name -->
                    <div class="flex flex-col">
                        <label for="name" class="font-semibold text-sm text-gray mb-2">User Name</label>
                        <x-input name="name" type="text" placeholder="User's Name" required class="w-full"/>
                        @error('name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- User Email -->
                    <div class="flex flex-col">
                        <label for="email" class="font-semibold text-sm text-gray mb-2">Email</label>
                        <x-input name="email" type="email" placeholder="email" required class="w-full"/>
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- User Phone Number -->
                    <div class="flex flex-col">
                        <label for="contact_number" class="font-semibold text-sm text-gray mb-2">Contact Number</label>
                        <x-input name="contact_number" type="text" placeholder="01X-XXXXXXXX" required class="w-full"/>
                        @error('contact_number')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- Role -->
                    <div class="flex flex-col">
                        <label for="role" class="font-semibold text-sm text-gray mb-2">Role</label>
                        <select name="role" id="role" required class="border border-gray-300 rounded-md p-2 w-full">
                            <option value="" disabled selected>Select Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }} class="font-semibold">Admin</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }} class="font-semibold">User</option>
                        </select>
                        @error('role')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- Password -->
                    <div class="flex flex-col">
                        <label for="password" class="font-semibold text-sm text-gray mb-2">Password</label>
                        <x-input name="password" type="text" placeholder="password" required class="w-full"/>
                        @error('password')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    
                <!-- Action Buttons -->
                <div class="flex justify-end items-center mt-4 w-full">
                    <a href="{{ route('user.list') }}"><x-secondary-button class="mr-2">CANCEL</x-secondary-button></a>
                    <x-button type="submit">Add</x-button>
                </div>
            </form>
        </div>
    </div>
    
</x-app-layout>