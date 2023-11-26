var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import Vector2 from "./utils/Vector2";
import { GridStack } from "gridstack";
export class Widget {
    constructor(id, endpoint, position = null, size = null) {
        this._hasSettings = false;
        this._gridItemElement = null;
        this._contentElement = null;
        this._headerElement = null;
        this._content = '<div class="grid-stack-item"><div class="grid-stack-item-content">\n' +
            '            <div class="h-8 flex absolute top-0 left-0 right-0 bottom-0 z-10" x-bind:class="!isEditing() ? \'hidden\' : \'\'">\n' +
            '                <div class="flex-grow header px-2 pt-2 grid grid-cols-2 gap-2 cursor-move text-sm font-semibold leading-tight bg-base-300/80 text-base-content hover:bg-primary/80 hover:text-primary-content" style="overflow:hidden;white-space: nowrap;">\n' +
            '                    <div class="truncate">Widget</div>\n' +
            '                </div>\n' +
            '            </div>\n' +
            '<div style="height:100%; width:100%; display: flex;justify-content: center;align-items: center;" class="bg-base-200"><i class="fa fa-refresh fa-spin"></i></div></div></div>';
        this._id = id;
        this._endpoint = endpoint;
        this._position = position !== null && position !== void 0 ? position : new Vector2(0, 0);
        this._size = size !== null && size !== void 0 ? size : new Vector2(1, 1);
        this._content = document.getElementById("widget-template").innerHTML;
    }
    load() {
        return __awaiter(this, void 0, void 0, function* () {
            this._contentElement.innerHTML = '<div style="height:100%; width:100%; display: flex;justify-content: center;align-items: center;" class="bg-base-200"><i class="fa fa-refresh fa-spin"></i></div>';
            let response = yield fetch(this._endpoint);
            this._content = yield response.text();
            this._headerElement.innerHTML = "Widget " + this._id + " - " + this._size.x + "x" + this._size.y;
            this._contentElement.innerHTML = this._content;
            let node = document.createElement('div');
            node.setAttribute('style', 'position: absolute; bottom: 0.5rem; right: 0.5rem; z-index: 10;');
            node.innerHTML = '<button class="btn btn-xs btn-ghost" x-on:click="refreshWidget($refs.widget)"><i class="fa fa-refresh"></i></button>';
            this._contentElement.appendChild(node);
        });
    }
    get content() {
        return this._content;
    }
    get id() {
        return this._id;
    }
    get gridItemElement() {
        return this._gridItemElement;
    }
    set gridItemElement(value) {
        this._gridItemElement = value;
        this._gridItemElement.setAttribute('data-id', this._id.toString());
        this._contentElement = this._gridItemElement.getElementsByClassName('widget-content')[0];
        this._headerElement = this._gridItemElement.getElementsByClassName('widget-header')[0];
    }
    get position() {
        return this._position;
    }
    set position(value) {
        this._position = value;
    }
    get size() {
        return this._size;
    }
    set size(value) {
        this._size = value;
    }
    get hasSettings() {
        return this._hasSettings;
    }
    set hasSettings(value) {
        this._hasSettings = value;
    }
}
export class WidgetStack {
    constructor() {
        this._widgets = [];
        this._isEditing = false;
        this._grid = null;
        this.initGrid();
        for (let i = 0; i < 22; i++) {
            let col = i % 6 * 2;
            let row = Math.floor(i / 6) * 2;
            this.addWidget(new Widget(i, '/xhr/v1/dashboard/widgets/' + i, new Vector2(col, row), new Vector2(2, 2)));
        }
        this.loadWidgets();
    }
    initGrid() {
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
    toggleEditing() {
        var _a;
        this._isEditing = !this._isEditing;
        (_a = this._grid) === null || _a === void 0 ? void 0 : _a.destroy(false);
        this.initGrid();
    }
    onDragStart(event, el) {
        var _a;
        (_a = el.querySelector('.header')) === null || _a === void 0 ? void 0 : _a.classList.add('active');
    }
    onDragStop(event, el) {
        var _a;
        (_a = el.querySelector('.header')) === null || _a === void 0 ? void 0 : _a.classList.remove('active');
    }
    addWidget(widget) {
        var _a;
        let gie = (_a = this._grid) === null || _a === void 0 ? void 0 : _a.addWidget(widget.content, {
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
    loadWidgets() {
        var _a, _b;
        return __awaiter(this, void 0, void 0, function* () {
            (_a = this._grid) === null || _a === void 0 ? void 0 : _a.batchUpdate(true);
            for (let widget of this._widgets) {
                yield widget.load();
            }
            (_b = this._grid) === null || _b === void 0 ? void 0 : _b.batchUpdate(false);
            this.onWidgetsFinishedLoading();
        });
    }
    onWidgetsFinishedLoading() {
    }
    getWidgetById(id) {
        let widgetIdx = this._widgets.findIndex(widget => widget.id === id);
        if (widgetIdx === -1) {
            return null;
        }
        return this._widgets[widgetIdx];
    }
    removeWidgetById(id) {
        var _a;
        let widgetIdx = this._widgets.findIndex(widget => widget.id === id);
        if (widgetIdx === -1) {
            return;
        }
        let widget = this._widgets[widgetIdx];
        this._widgets.splice(widgetIdx, 1);
        if (widget.gridItemElement) {
            (_a = this._grid) === null || _a === void 0 ? void 0 : _a.removeWidget(widget.gridItemElement, true);
        }
    }
    removeWidget(widget) {
        this.removeWidgetById(widget.id);
    }
    refreshWidgetById(id) {
        let widget = this.getWidgetById(id);
        if (!widget) {
            console.error(`Widget with id ${id} not found!`);
            return;
        }
        widget.load();
    }
    refreshWidget(widget) {
        this.refreshWidgetById(widget.id);
    }
    saveChanges() {
    }
    get widgets() {
        return this._widgets;
    }
    get isEditing() {
        return this._isEditing;
    }
    set isEditing(value) {
        this._isEditing = value;
    }
    get gridStack() {
        return this._grid;
    }
}
//# sourceMappingURL=dashboard.js.map