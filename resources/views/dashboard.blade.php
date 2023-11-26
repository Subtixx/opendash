@push('styles')
    <link rel="stylesheet" href="{{ Vite::asset('css/gridstack.min.css') }}">
    <link rel="stylesheet" href="{{ Vite::asset('css/gridstack-extra.min.css') }}">
@endpush
@push('scripts')
    <script src="{{ Vite::asset('js/gridstack-all.js') }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('gridstack', () => ({
                widgetStack: null,
                init() {
                    this.widgetStack = new WidgetStack();
                },
                isEditing() {
                    return this.widgetStack.isEditing;
                },
                toggleEditing() {
                    this.widgetStack.toggleEditing();
                },
                removeWidget(el) {
                    this.widgetStack.removeWidgetById(parseInt(el.getAttribute('data-id')));
                },
                refreshWidget(el){
                    this.widgetStack.refreshWidgetById(parseInt(el.getAttribute('data-id')));
                },
                saveChanges() {
                    this.widgetStack.saveChanges();
                },
            }));
            /*
            Alpine.data('gridstack', () => ({
                isEditing: false,
                grid: null,
                chart: null,
                processing: false,
                widgetsBeforeSave: [],
                toggleEditing(discardChanges = true) {
                    this.isEditing = !this.isEditing;
                    if (!this.isEditing) {
                        if (discardChanges) {
                            this.discardChanges();
                            return;
                        }
                    } else {
                        this.widgetsBeforeSave = this.grid.engine.nodes
                            .sort((a, b) => {
                                return a.el.dataset.id - b.el.dataset.id;
                            })
                            .map((node) => {
                                return {
                                    id: parseInt(node.el.dataset.id),
                                    x: node.x || 0,
                                    y: node.y || 0,
                                    w: node.w || 1,
                                    h: node.h || 1
                                };
                            });
                    }

                    this.grid.destroy(false);
                    this.initGrid();
                    if (!this.isEditing) {
                        this.grid.disable();
                    }
                },
                discardChanges() {
                    this.grid.destroy(false);
                    this.initGrid();
                    this.grid.disable();

                    this.grid.batchUpdate();
                    for (let i = 0; i < this.widgetsBeforeSave.length; i++) {
                        let widget = this.widgetsBeforeSave[i];
                        let el = this.grid.engine.nodes.find((node) => {
                            return node.el.dataset.id === widget.id.toString();
                        });
                        this.grid.update(el.el, {
                            x: widget.x,
                            y: widget.y,
                            w: widget.w,
                            h: widget.h,
                        });
                    }
                    this.grid.commit();
                    this.isEditing = false;
                    this.widgetsBeforeSave = [];
                },
                initGrid: function () {
                    this.grid = GridStack.init({
                        handle: '.header',
                        margin: 10,
                        cellHeight: '6.5rem',
                    });
                    this.grid.on('dragstart', function (event, el) {
                        el.querySelector('.header').classList.add('active');
                    })
                        .on('dragstop', function (event, el) {
                            el.querySelector('.header').classList.remove('active');
                        });
                },
                init() {
                    this.initGrid();
                    this.grid.disable();
                },
                removeWidget(el) {
                    console.log(el)
                    el.remove();
                    this.grid.removeWidget(el);
                },
                saveChanges() {
                    let newItems = this.grid.engine.nodes
                        .sort((a, b) => {
                            return a.el.dataset.id - b.el.dataset.id;
                        })
                        .map((node) => {
                            return {
                                id: parseInt(node.el.dataset.id),
                                x: node.x || 0,
                                y: node.y || 0,
                                width: node.w || 1,
                                height: node.h || 1,
                            };
                        });
                    this.grid.destroy(false);
                    this.isEditing = false;
                    axios.post('/dashboard/save', {widgets: newItems})
                        .then((response) => {
                            if (response.data.success) {
                                this.$dispatch('toast', {
                                    type: 'success',
                                    body: 'Changes saved!'
                                });

                                this.grid.destroy(false);
                                this.initGrid();
                                this.grid.disable();
                            }
                        })
                        .catch((error) => {
                            this.$dispatch('toast', {
                                type: 'error',
                                body: error,
                            });
                            console.error(error);
                        });
                },
            }));
            */
        });
    </script>
@endpush

<x-app-layout>
    <div class="py-12" x-data="gridstack">
        <div class="mx-auto sm:px-6 lg:px-8">
            <button class="btn" x-bind:class="isEditing() ? 'btn-primary' : 'btn-secondary'"
                    x-on:click="toggleEditing()">
                <span x-show="!isEditing()">Edit</span>
                <span x-show="isEditing()">Done</span>
            </button>
            <div class="grid-stack">
            </div>
        </div>
    </div>
    {{--<div class="py-12" x-data="gridstack">
        <div class="mx-auto sm:px-6 lg:px-8">
            <button class="btn" x-bind:class="isEditing ? 'btn-primary' : 'btn-secondary'"
                    x-on:click="toggleEditing()">
                <span x-show="!isEditing">Edit</span>
                <span x-show="isEditing">Done</span>
            </button>
            <button class="btn btn-primary" x-show="isEditing"
                    x-on:click="saveChanges()">Save
            </button>
            <div class="grid-stack">
                @foreach($widgets as $widget)
                    <x-grid-item gs-w="{{$widget->width}}" gs-h="{{$widget->height}}"
                                 gs-x="{{$widget->x}}" gs-y="{{$widget->y}}"
                                 gs-min-w="{{$widget->constraints['min_width']}}"
                                 gs-min-h="{{$widget->constraints['min_height']}}"
                                 gs-max-w="{{$widget->constraints['max_width']}}"
                                 gs-max-h="{{$widget->constraints['max_height']}}"
                                 gs-no-resize="{{$widget->canResize ? '0' : '1'}}"
                                 has-settings="{{ $widget->widget->hasSettings() }}"
                                 data-id="{{$widget->id}}"
                    >
                        <x-slot name="header">
                            {{ $widget->name }}
                        </x-slot>
                        {{$widget->widget->render()}}
                    </x-grid-item>
                @endforeach
            </div>
            <canvas id="myChart" width="400" height="400"></canvas>
        </div>
    </div>--}}

    <div class="hidden" id="widget-template">
        <div class="grid-stack-item"
             gs-w="2" gs-h="2"
             gs-min-w="2" gs-min-h="2"
             gs-max-w="6" gs-max-h="6"
             gs-no-resize="0"
             x-ref="widget" x-data="{ isWidgetEditing() { if(typeof isEditing === 'function') { return isEditing(); } return false; } }">
            <div class="grid-stack-item-content"
                 x-bind:class="isWidgetEditing() ? 'shadow-sm sm:rounded-lg border-2 border-primary' : ''">
                <div x-bind:class="!isWidgetEditing() ? 'hidden' : ''">
                    <div class="h-8 flex absolute top-0 left-0 right-0 bottom-0 z-10">
                        <div class="flex-grow header px-2 pt-2 grid grid-cols-2 gap-2 cursor-move
                    text-sm font-semibold leading-tight
                    bg-base-300/80 text-base-content
                    hover:bg-primary/80 hover:text-primary-content widget-header"
                             style="overflow:hidden;white-space: nowrap;">
                        </div>

                        <button class="btn btn-info btn-xs rounded-none" style="height: 100%;"
                                x-on:click="refreshWidget($refs.widget)">
                            <i class="fa fa-refresh"></i>
                        </button>
                        <button class="btn btn-warning btn-xs rounded-none" style="height: 100%;">
                            <x-heroicon-m-adjustments-horizontal class="w-4 h-4"/>
                        </button>
                        <button class="btn btn-error btn-xs rounded-none" style="height: 100%;"
                                x-on:click="removeWidget($refs.widget)">
                            <x-heroicon-o-x-mark class="w-4 h-4"/>
                        </button>
                    </div>
                </div>
                <div class="overflow-auto widget-content" style="height: 100%;">
                    <div style="height:100%; width:100%; display: flex;justify-content: center;align-items: center;"
                         class="bg-base-100 p-4 rounded-box h-full">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
