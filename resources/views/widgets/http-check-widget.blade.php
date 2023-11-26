<button class="flex flex-row h-full items-center bg-base-100 rounded-xl p-2 text-left"
        :class="!isEditing ? 'hover:cursor-pointer hover:bg-base-200' : 'hover:cursor-default'"
        x-on:click="!isEditing ? window.open('{{ $url }}', '_blank') : null"
>
    <div class="flex items-center justify-center flex-shrink-0 rounded-xl">
        <div class="indicator">
            <span @class([
            'text-center',
            'cursor-pointer',
            'indicator-item',
            'badge',
            'badge-xs',
            'badge-success' => $isUp,
            'badge-error' => ! $isUp,
        ]) x-tooltip="{
                content: () => $refs.template.innerHTML,
                allowHTML: true,
                appendTo: document.body,
            }">
                <template x-ref="template">
                    <p><i>Response code:</i>
                        <span class="font-black">{{ $response['code'] }}</span></p>
                    <p><i>Response time:</i>
                        <span class="font-black">{{ sprintf('%.1f', $response['time']) }} ms</span></p>
                </template>
            </span>
            <i @class([$icon, "h-12","w-12"])
               style="font-size: xx-large;vertical-align: middle;line-height: 1.5em;text-align: center;"></i>
        </div>
    </div>
    <div class="flex flex-col flex-grow ml-2">
        <div class="text-sm text-base-content/80">
            {{ $name }}
        </div>
        <div class="text-sm">
            {{ $description }}
        </div>
    </div>
</button>
