<x-app-layout>
    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">Redeem Reward Information</p>
            </div>
    </div>
    <div class="mt-2 mx-4">
        <form method="GET" enctype="multipart/form-data">
            @csrf
            <div class="flex space-x-6 mt-4">
                <!-- Reward Image -->
                <div class="flex flex-col w-1/2">
                        <div class="mt-2">
                            <img src="{{ asset('images/rewards/' . $redeemReward->reward->reward_image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-md">
                        </div>
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Name -->
                <div class="flex flex-col w-1/2">
                    <label for="user_id" class="font-semibold text-sm text-gray mb-2">Reward ID</label>
                    <x-input name="user_id" type="text" placeholder="User ID" value="{{ $redeemReward->id }}" disabled/>
                    @error('user_id')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Description -->
                <div class="flex flex-col w-1/2">
                    <label for="user_name" class="font-semibold text-sm text-gray mb-2">User Name</label>
                    <x-input name="user_name" type="text" placeholder="user name" value="{{ $redeemReward->user->name }}" disabled/>
                    @error('user_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Name -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_name" class="font-semibold text-sm text-gray mb-2">Reward Name</label>
                    <x-input name="reward_name" type="text" placeholder="Reward's Name" value="{{ $redeemReward->reward->reward_name }}" disabled/>
                    @error('reward_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Description -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_description" class="font-semibold text-sm text-gray mb-2">Reward Description</label>
                    <x-input name="reward_description" type="text" placeholder="desc" value="{{ $redeemReward->reward->reward_description }}" disabled/>
                    @error('reward_description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Code -->
                <div class="flex flex-col w-1/2">
                    <label for="redeem_code" class="font-semibold text-sm text-gray mb-2">Reward Code</label>
                    <x-input name="redeem_code" type="text" placeholder="Reward's Code" value="{{ $redeemReward->redeem_code }}" disabled/>
                    @error('redeem_code')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Expired Date -->
                <div class="flex flex-col w-1/2">
                    <label for="code_expired_date" class="font-semibold text-sm text-gray mb-2">Reward Expired Date</label>
                    <x-input name="code_expired_date" type="text" placeholder="desc" value="{{ $redeemReward->code_expired_date }}" disabled/>
                    @error('code_expired_date')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Point Required -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_point_required" class="font-semibold text-sm text-gray mb-2">Reward Point Used</label>
                    <x-input name="reward_point_required" type="text" placeholder="Reward's Code" value="{{ $redeemReward->reward->reward_point_required }}" disabled/>
                    @error('reward_point_required')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Reward Status -->
                <div class="flex flex-col w-1/2">
                    <label for="redeem_code_status" class="font-semibold text-sm text-gray mb-2">Code Status</label>
                    <x-input name="redeem_code_status" type="text" placeholder="Reward's Code" value="{{ $redeemReward->redeem_code_status }}" disabled/>
                    @error('redeem_code_status')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end items-center mt-4">
                <a href="{{ route('my.reward.list') }}"><x-secondary-button class="mr-2">CANCEL</x-secondary-button></a>
            </div>
        </form>
    </div>    
</x-app-layout>