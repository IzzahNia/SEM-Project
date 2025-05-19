@if(auth()->user()->hasRole('admin'))
<div class="grid mx-10 mb-6">
    <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] w-full">
        <div class="flex self-stretch px-4 mt-1 border-b-2 border-b-primary-200">
            <p class="font-bold text-base py-2 px-4 text-primary-900">{{ $title }}</p>
        </div>
        <div class="flex justify-left px-4">
            <p class="font-semibold text-base py-2 px-4">{{ $data }}</p>
        </div>
    </div>
</div>

@else
<div class="grid mx-10 mb-6">
    <div class="rounded-md bg-white drop-shadow-[0px_0px_12px_rgba(120,120,120,0.15)] w-full">
        <div class="flex self-stretch px-4 mt-1 border-b-2 border-b-purple-500">
            <p class="font-bold text-base py-2 px-4 text-purple-900">{{ $title }}</p>
        </div>
        <div class="flex justify-left px-4">
            <p class="font-semibold text-base py-2 px-4">{{ $data }}</p>
        </div>
    </div>
</div>
@endif