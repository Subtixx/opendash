<div class="bg-base-100 p-4 rounded-box h-full">
    <div class="flex flex-row h-full items-center">
        <div class="flex items-center justify-center flex-shrink-0 h-12 w-12 rounded-xl {{$color}}">
            <x-dynamic-component :component="$icon" class="w-6 h-6"/>
        </div>
        <div class="flex flex-col flex-grow ml-4">
            <div class="text-sm text-base-content/80">
                {{ $title }}
            </div>
            <div class="font-bold text-lg">
                {{ $value }}
            </div>
        </div>
    </div>
</div>
