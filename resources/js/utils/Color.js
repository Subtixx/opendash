import MathUtils from "./MathUtils";
export default class Color {
    constructor(red, green, blue, alpha = 1) {
        this._red = MathUtils.clamp(red, 0, 255);
        this._green = MathUtils.clamp(green, 0, 255);
        this._blue = MathUtils.clamp(blue, 0, 255);
        this._alpha = MathUtils.clamp(alpha, 0, 1);
    }
    static _fromCssColor(clr) {
        const canvas = document.createElement("canvas");
        canvas.width = canvas.height = 1;
        const ctx = canvas.getContext("2d", { willReadFrequently: true });
        if (!ctx) {
            throw new Error("Could not get canvas context");
        }
        ctx.fillStyle = clr;
        ctx.fillRect(0, 0, 1, 1);
        return ctx.getImageData(0, 0, 1, 1).data;
    }
    static _fromCssVar(varName) {
        return getComputedStyle(document.documentElement).getPropertyValue(varName);
    }
    static fromCssVar(varName, type = "oklch") {
        let colorStr = this._fromCssVar(varName);
        let colorInt = this._fromCssColor(`${type}(${colorStr})`);
        return new Color(colorInt[0], colorInt[1], colorInt[2], colorInt[3]);
    }
    static fromCssColor(clr) {
        let colorInt = this._fromCssColor(clr);
        return new Color(colorInt[0], colorInt[1], colorInt[2], colorInt[3]);
    }
    toHex() {
        return "#" + ((1 << 24) + (this.red << 16) + (this.green << 8) + this.blue).toString(16).slice(1);
    }
    toRgba() {
        return `rgba(${this.red},${this.green},${this.blue},${this.alpha})`;
    }
    get red() {
        return this._red;
    }
    set red(value) {
        this._red = MathUtils.clamp(value, 0, 255);
    }
    get green() {
        return this._green;
    }
    set green(value) {
        this._green = MathUtils.clamp(value, 0, 255);
    }
    get blue() {
        return this._blue;
    }
    set blue(value) {
        this._blue = MathUtils.clamp(value, 0, 255);
    }
    get alpha() {
        return this._alpha;
    }
    set alpha(value) {
        this._alpha = MathUtils.clamp(value, 0, 1);
    }
    toString() {
        return this.toHex();
    }
}
//# sourceMappingURL=Color.js.map