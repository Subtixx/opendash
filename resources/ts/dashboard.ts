import Vector2 from "./utils/Vector2";
import {GridStack} from "gridstack";
import {GridItemHTMLElement} from "gridstack/dist/types";

export class Widget {
    private readonly _id: number;
    private _position: Vector2;
    private _size: Vector2;
    private _hasSettings: boolean = false;
    private _gridItemElement: GridItemHTMLElement | null = null;
    private readonly _endpoint: string;

    private _contentElement: Element | null = null;
    private _headerElement: Element | null = null;
    private _content: string = '<div class="grid-stack-item"><div class="grid-stack-item-content">\n' +
        '            <div class="h-8 flex absolute top-0 left-0 right-0 bottom-0 z-10" x-bind:class="!isEditing() ? \'hidden\' : \'\'">\n' +
        '                <div class="flex-grow header px-2 pt-2 grid grid-cols-2 gap-2 cursor-move text-sm font-semibold leading-tight bg-base-300/80 text-base-content hover:bg-primary/80 hover:text-primary-content" style="overflow:hidden;white-space: nowrap;">\n' +
        '                    <div class="truncate">Widget</div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '<div style="height:100%; width:100%; display: flex;justify-content: center;align-items: center;" class="bg-base-200"><i class="fa fa-refresh fa-spin"></i></div></div></div>';

    constructor(id: number, endpoint: string, position: Vector2 | null = null, size: Vector2 | null = null) {
        this._id = id;
        this._endpoint = endpoint;
        this._position = position ?? new Vector2(0, 0);
        this._size = size ?? new Vector2(1, 1);

        this._content = document.getElementById("widget-template")!.innerHTML;
    }

    async load(): Promise<void> {
        this._contentElement!.innerHTML = '<div style="height:100%; width:100%; display: flex;justify-content: center;align-items: center;" class="bg-base-200"><i class="fa fa-refresh fa-spin"></i></div>';
        let response = await fetch(this._endpoint);
        this._content = await response.text();
        this._headerElement!.innerHTML = "Widget " + this._id + " - " + this._size.x + "x" + this._size.y;
        this._contentElement!.innerHTML = this._content;

        let node = document.createElement('div');
        node.setAttribute('style', 'position: absolute; bottom: 0.5rem; right: 0.5rem; z-index: 10;');
        node.innerHTML = '<button class="btn btn-xs btn-ghost" x-on:click="refreshWidget($refs.widget)"><i class="fa fa-refresh"></i></button>';
        this._contentElement!.appendChild(node);
    }

    get content(): string {
        return this._content;
    }

    get id(): number {
        return this._id;
    }

    get gridItemElement(): GridItemHTMLElement | null {
        return this._gridItemElement;
    }

    set gridItemElement(value: GridItemHTMLElement) {
        this._gridItemElement = value;

        this._gridItemElement.setAttribute('data-id', this._id.toString());
        this._contentElement = this._gridItemElement.getElementsByClassName('widget-content')[0];
        this._headerElement = this._gridItemElement.getElementsByClassName('widget-header')[0];
    }

    get position(): Vector2 {
        return this._position;
    }

    set position(value: Vector2) {
        this._position = value;
    }

    get size(): Vector2 {
        return this._size;
    }

    set size(value: Vector2) {
        this._size = value;
    }

    get hasSettings(): boolean {
        return this._hasSettings;
    }

    set hasSettings(value: boolean) {
        this._hasSettings = value;
    }
}

export class WidgetStack {
    private _widgets: Widget[] = [];
    private _isEditing: boolean = false;
    private _grid: GridStack | null = null;

    constructor() {
        this.initGrid();

        for (let i = 0; i < 22; i++) {
            let col = i % 6 * 2;
            let row = Math.floor(i / 6) * 2;
            this.addWidget(new Widget(
                    i,
                    '/xhr/v1/dashboard/widgets/' + i,
                    new Vector2(col, row),
                    new Vector2(2, 2)
                )
            );
        }
        this.loadWidgets();
    }

    private initGrid() {
        this._grid = GridStack.init({
            handle: '.header',
            margin: 10,
            cellHeight: '6.5rem',
        });
        this._grid.on('dragstart', this.onDragStart.bind(this))
            .on('dragstop', this.onDragStop.bind(this));
        if (!this._isEditing) {
            this._grid.disable();
        }
    }

    public toggleEditing(): void {
        this._isEditing = !this._isEditing;
        this._grid?.destroy(false);
        this.initGrid();
    }

    private onDragStart(event: Event, el: GridItemHTMLElement): void {
        el.querySelector('.header')?.classList.add('active');
    }

    private onDragStop(event: Event, el: GridItemHTMLElement): void {
        el.querySelector('.header')?.classList.remove('active');
    }

    public addWidget(widget: Widget): void {
        let gie = this._grid?.addWidget(widget.content, {
            x: widget.position.x,
            y: widget.position.y,
            w: widget.size.x,
            h: widget.size.y,
        });
        if (!gie) {
            console.error("Failed to create grid item element!");
            return;
        }
        widget.gridItemElement = gie;
        this._widgets.push(widget);
    }

    async loadWidgets(): Promise<void> {
        this._grid?.batchUpdate(true);
        for (let widget of this._widgets) {
            await widget.load();
        }
        this._grid?.batchUpdate(false);
        this.onWidgetsFinishedLoading();
    }

    private onWidgetsFinishedLoading(): void {
    }

    public getWidgetById(id: number): Widget | null {
        let widgetIdx = this._widgets.findIndex(widget => widget.id === id);
        if (widgetIdx === -1) {
            return null;
        }
        return this._widgets[widgetIdx];
    }

    public removeWidgetById(id: number): void {
        let widgetIdx = this._widgets.findIndex(widget => widget.id === id);
        if (widgetIdx === -1) {
            return;
        }
        let widget = this._widgets[widgetIdx];
        this._widgets.splice(widgetIdx, 1);

        if (widget.gridItemElement) {
            this._grid?.removeWidget(widget.gridItemElement, true);
        }
    }

    public removeWidget(widget: Widget): void {
        this.removeWidgetById(widget.id);
    }

    public refreshWidgetById(id: number): void {
        let widget = this.getWidgetById(id);
        if (!widget) {
            console.error(`Widget with id ${id} not found!`);
            return;
        }
        widget.load();
    }

    public refreshWidget(widget: Widget): void {
        this.refreshWidgetById(widget.id);
    }

    public saveChanges(): void {

    }

    public get widgets(): Widget[] {
        return this._widgets;
    }

    public get isEditing(): boolean {
        return this._isEditing;
    }

    public set isEditing(value: boolean) {
        this._isEditing = value;
    }

    public get gridStack(): GridStack | null {
        return this._grid;
    }
}
