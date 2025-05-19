<x-app-layout>
    <div class="mx-10 my-6">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">Reward Redemption Information</p>
            </div>

            <div class="flex justify-between items-center w-auto mt-4">
                <p class="font-bold text-md">Reward Redeem Successful !!!</p>
            </div>
    </div>
    <div class="mt-2 mx-4">
        <form method="GET" enctype="multipart/form-data">
            @csrf
            <div class="flex space-x-6 mt-4">
                <!-- Reward Image -->
                <div class="flex flex-col w-1/2">
                        <div class="mt-2">
                            <img src="{{ asset('images/rewards/' . $reward_image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-md">
                        </div>
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Name -->
                <div class="flex flex-col w-1/2">
                    <label for="user_id" class="font-semibold text-sm text-gray mb-2">User ID</label>
                    <x-input name="user_id" type="text" placeholder="User ID" value="{{ $user_id }}" disabled/>
                    @error('user_id')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Description -->
                <div class="flex flex-col w-1/2">
                    <label for="user_name" class="font-semibold text-sm text-gray mb-2">User Name</label>
                    <x-input name="user_name" type="text" placeholder="user name" value="{{ $user_name }}" disabled/>
                    @error('user_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Name -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_name" class="font-semibold text-sm text-gray mb-2">Reward Name</label>
                    <x-input name="reward_name" type="text" placeholder="Reward's Name" value="{{ $reward_name }}" disabled/>
                    @error('reward_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Description -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_description" class="font-semibold text-sm text-gray mb-2">Reward Description</label>
                    <x-input name="reward_description" type="text" placeholder="desc" value="{{ $reward_description }}" disabled/>
                    @error('reward_description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end items-center mt-4">
                <a href="{{ route('reward.redemption') }}"><x-secondary-button class="mr-2">CANCEL</x-secondary-button></a>
            </div>
        </form>
    </div>    
</x-app-layout>