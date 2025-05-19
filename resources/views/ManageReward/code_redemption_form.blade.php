<x-app-layout>
    <div class="grid grid-cols-3 gap-12 mx-10 my-6">
            <div class="flex justify-between items-center w-auto">
                <p class="font-bold text-md">Reward Redemption</p>
            </div>
    </div>
    <div class="mt-2 mx-4">
        <form action="{{ route('redeem.reward.code') }}" method="POST">
            @csrf
            <div class="flex space-x-6 mt-4">
                <!-- Reward Name -->
                <div class="flex flex-col w-1/2">
                    <label for="redeem_code" class="font-bold text-sm text-gray mb-2">Enter Rewards Code</label>
                    <x-code-input name="redeem_code" type="text" placeholder="XXXXXX" value="{{ old('redeem_code') }}" required/>
                    @error('redeem_code')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end items-center mt-4">
                <a href="{{ route('reward.list') }}"><x-secondary-button class="mr-2">CANCEL</x-secondary-button></a>
                <x-button type="submit">CONFIRM</x-button>
            </div>
        </form>
    </div>    

    
</x-app-layout>