<div {{ $attributes->merge([
    'class' => 'grid-stack-item',
    'gs-w' => '2', 'gs-h' => '2',
    'gs-min-w' => '2', 'gs-min-h' => '2',
    'gs-max-w' => '6', 'gs-max-h' => '6',
    'gs-no-resize' => '0'
    ]) }} x-ref="widget" x-data="{}">
    <div class="grid-stack-item-content"
         x-bind:class="isEditing() ? 'shadow-sm sm:rounded-lg border-2 border-primary' : ''">
        <div x-bind:class="!isEditing() ? 'hidden' : ''">
            <div class="h-8 flex absolute top-0 left-0 right-0 bottom-0 z-10">
                <div class="flex-grow header px-2 pt-2 grid grid-cols-2 gap-2 cursor-move
                    text-sm font-semibold leading-tight
                    bg-base-300/80 text-base-content
                    hover:bg-primary/80 hover:text-primary-content" style="overflow:hidden;white-space: nowrap;">
                    @if (isset($header))
                        {{ $header }}
                    @endif
                </div>

                @if(isset($hasSettings) && $hasSettings)
                    <button class="btn btn-warning btn-xs rounded-none" style="height: 100%;">
                        <x-heroicon-m-adjustments-horizontal class="w-4 h-4"/>
                    </button>
                @endif
                <button class="btn btn-error btn-xs rounded-none" style="height: 100%;"
                        x-on:click="removeWidget($refs.widget)">
                    <x-heroicon-o-x-mark class="w-4 h-4"/>
                </button>
            </div>
        </div>
        <div class="overflow-auto" style="height: 100%;">
            {!! $slot !!}
        </div>
    </div>
</div>
