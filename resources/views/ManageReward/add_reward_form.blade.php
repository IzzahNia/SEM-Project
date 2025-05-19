<x-app-layout>
    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">Add Reward</p>
            </div>
    </div>
    <div class="mt-2 mx-4">
        <form action="{{ route('create.reward') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex space-x-6 mt-4">
                <!-- Reward Name -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_name" class="font-semibold text-sm text-gray mb-2">Reward Name</label>
                    <x-input name="reward_name" type="text" placeholder="Reward's Name" value="{{ old('reward_name') }}" required/>
                    @error('reward_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Description -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_description" class="font-semibold text-sm text-gray mb-2">Reward Description</label>
                    <x-input name="reward_description" type="text" placeholder="desc" value="{{ old('reward_description') }}" required/>
                    @error('reward_description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Datetime -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_duration_date" class="font-semibold text-sm text-gray mb-2">Reward Duration Date</label>
                    <x-input name="reward_duration_date" type="datetime-local" value="{{ old('reward_duration_date') }}" required/>
                    @error('reward_duration_date')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Quantity -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_quantity" class="font-semibold text-sm text-gray mb-2">Reward Quantity</label>
                    <x-input name="reward_quantity" type="text" placeholder="Reward Quantity" value="{{ old('reward_quantity') }}" required/>
                    @error('reward_quantity')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Point Required -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_point_required" class="font-semibold text-sm text-gray mb-2">Reward Point Required</label>
                    <x-input name="reward_point_required" type="text" placeholder="Reward Point Required" value="{{ old('reward_point_required') }}" required/>
                    @error('reward_point_required')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Reward Status -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_status" class="font-semibold text-sm text-gray mb-2">Reward Status</label>
                    <select name="reward_status" id="reward_status" required class="border border-gray-300 rounded-md p-2">
                        <option value="" disabled selected>Select Status</option>
                        <option value="Available" {{ old('reward_status') == 'Available' ? 'selected' : '' }} class="font-semibold">Available</option>
                        <option value="Unavailable" {{ old('reward_status') == 'Unavailable' ? 'selected' : '' }} class="font-semibold">Unavailable </option>
                    </select>
                    @error('reward_status')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>            
            </div>

            <div class="flex space-x-6 mt-4">
                <div class="flex flex-col w-1/2">
                    <label for="reward_image" class="font-semibold text-sm text-gray mb-2">Upload Image</label>
                    <input type="file" name="reward_image" id="reward_image" accept="image/*" required class="border border-gray-300 rounded-md p-2"/>
                    @error('reward_image')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="flex justify-end items-center mt-4">
                <a href="{{ route('reward.list') }}"><x-secondary-button class="mr-2">CANCEL</x-secondary-button></a>
                <x-button type="submit">Add</x-button>
            </div>
        </form>
    </div>    
</x-app-layout>