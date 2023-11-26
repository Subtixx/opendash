import MathUtils from "./MathUtils";

export default class Color {
    private _red: number;
    private _green: number;
    private _blue: number;
    private _alpha: number;

    constructor(red: number, green: number, blue: number, alpha: number = 1) {
        this._red = MathUtils.clamp(red, 0, 255);
        this._green = MathUtils.clamp(green, 0, 255);
        this._blue = MathUtils.clamp(blue, 0, 255);
        this._alpha = MathUtils.clamp(alpha, 0, 1);
    }

    private static _fromCssColor(clr: string): Uint8ClampedArray {
        const canvas = document.createElement("canvas");
        canvas.width = canvas.height = 1;
        const ctx = canvas.getContext("2d", {willReadFrequently: true});
        if (!ctx) {
            throw new Error("Could not get canvas context");
        }
        ctx.fillStyle = clr;
        ctx.fillRect(0, 0, 1, 1);
        return ctx.getImageData(0, 0, 1, 1).data;
    }

    private static _fromCssVar(varName: string) {
        return getComputedStyle(document.documentElement).getPropertyValue(varName);
    }

    static fromCssVar(varName: string, type: "rgba" | "oklch" | "hsl" | "hwb" | "hsv" | "rgb" = "oklch") {
        let colorStr = this._fromCssVar(varName);
        let colorInt = this._fromCssColor(`${type}(${colorStr})`);
        return new Color(colorInt[0], colorInt[1], colorInt[2], colorInt[3]);
    }

    static fromCssColor(clr: string) {
        let colorInt = this._fromCssColor(clr);
        return new Color(colorInt[0], colorInt[1], colorInt[2], colorInt[3]);
    }

    public toHex() {
        return "#" + ((1 << 24) + (this.red << 16) + (this.green << 8) + this.blue).toString(16).slice(1);
    }

    public toRgba() {
        return `rgba(${this.red},${this.green},${this.blue},${this.alpha})`;
    }

    get red(): number {
        return this._red;
    }

    set red(value: number) {
        this._red = MathUtils.clamp(value, 0, 255);
    }

    get green(): number {
        return this._green;
    }

    set green(value: number) {
        this._green = MathUtils.clamp(value, 0, 255);
    }

    get blue(): number {
        return this._blue;
    }

    set blue(value: number) {
        this._blue = MathUtils.clamp(value, 0, 255);
    }

    get alpha(): number {
        return this._alpha;
    }

    set alpha(value: number) {
        this._alpha = MathUtils.clamp(value, 0, 1);
    }

    public toString() {
        return this.toHex();
    }
}
