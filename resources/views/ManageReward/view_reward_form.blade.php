<x-app-layout>
    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">View Reward</p>
            </div>
    </div>
    <div class="mt-2 mx-4">
        <form action="{{ route('redeem.reward', $reward->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex space-x-6 mt-4">
                <!-- Reward Image -->
                <div class="flex flex-col w-1/2">
                    @if($reward->reward_image)
                        <div class="mt-2">
                            <img src="{{ asset('images/rewards/' . $reward->reward_image) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-md">
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Name -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_name" class="font-semibold text-sm text-gray mb-2">Reward Name</label>
                    <x-input name="reward_name" type="text" placeholder="Reward's Name" value="{{ $reward->reward_name }}" disabled/>
                    @error('reward_name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Description -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_description" class="font-semibold text-sm text-gray mb-2">Reward Description</label>
                    <x-input name="reward_description" type="text" placeholder="desc" value="{{ $reward->reward_description }}" disabled/>
                    @error('reward_description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Datetime -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_duration_date" class="font-semibold text-sm text-gray mb-2">Reward Duration Date</label>
                    <x-input name="reward_duration_date" type="datetime-local" value="{{$reward->reward_duration_date }}" disabled/>
                    @error('reward_duration_date')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
    
                <!-- Reward Quantity -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_quantity" class="font-semibold text-sm text-gray mb-2">Reward Quantity</label>
                    <x-input name="reward_quantity" type="text" placeholder="Reward Quantity" value="{{ $reward->reward_quantity }}" disabled/>
                    @error('reward_quantity')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="flex space-x-6 mt-4">
                <!-- Reward Point Required -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_point_required" class="font-semibold text-sm text-gray mb-2">Reward Point Required</label>
                    <x-input name="reward_point_required" type="text" placeholder="Reward Point Required" value="{{ $reward->reward_point_required }}" disabled/>
                    @error('reward_point_required')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Reward Status -->
                <div class="flex flex-col w-1/2">
                    <label for="reward_status" class="font-semibold text-sm text-gray mb-2">Reward Status</label>
                    <x-input name="reward_status" type="text" value="{{ $reward->reward_status }}" disabled/>
                    @error('reward_status')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>            
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end items-center mt-4">
                <a href="{{ route('reward.list') }}">
                    <x-secondary-button class="mr-2">CANCEL</x-secondary-button>
                </a>
                @if($reward->reward_quantity > 0 && $reward->reward_status !== 'Unavailable' && auth()->user()->current_reward_points >= $reward->reward_point_required)
                    <x-button type="button" data-modal-target="redeem-modal-{{ $reward->id }}" data-modal-toggle="redeem-modal-{{ $reward->id }}" class="rounded-full py-2 px-3 bg-green-50 border border-green-200 justify-center items-center hover:bg-green-100 ml-2">
                        REDEEM
                    </x-button>
                    <x-redeem-confirmation-modal 
                        route="{{ route('redeem.reward', $reward->id) }}" 
                        title="Redeem Reward" 
                        description="Are you sure to redeem '{{ $reward->reward_name }}'?" 
                        id="{{ $reward->id }}" 
                    />
                @elseif($reward->reward_quantity <= 0)
                    <x-button type="button" disabled class="rounded-full py-2 px-3 bg-red-600 text-gray-500 cursor-not-allowed ml-2">
                        OUT OF STOCK
                    </x-button>
                @elseif(auth()->user()->current_reward_points < $reward->reward_point_required)
                <x-button type="button" disabled style="background-color: #FFD700; color: #4B4B4B;" class="rounded-full py-2 px-3 cursor-not-allowed ml-2">
                    INSUFFICIENT REWARD POINTS
                    </x-button>
                @endif
            </div>
        </form>
    </div>    
</x-app-layout>
