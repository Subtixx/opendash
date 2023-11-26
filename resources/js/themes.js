import Color from "./utils/Color";
import Chart from "chart.js/auto";
function changeTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    let b3ColorRaw = Color.fromCssVar('--b3');
    const b3Color = b3ColorRaw.toHex();
    let bcColorRaw = Color.fromCssVar('--bc');
    const bcColor = bcColorRaw.toHex();
    let infoColorRaw = Color.fromCssVar('--in');
    const infoColor = infoColorRaw.toHex();
    let successColorRaw = Color.fromCssVar('--su');
    const successColor = successColorRaw.toHex();
    let warningColorRaw = Color.fromCssVar('--war');
    const warningColor = warningColorRaw.toHex();
    let errorColorRaw = Color.fromCssVar('--er');
    const errorColor = errorColorRaw.toHex();
    const dataSetColors = [infoColor, successColor, warningColor, errorColor];
    // Override default chart colors.
    Chart.defaults.backgroundColor = b3Color;
    Chart.defaults.borderColor = b3Color;
    Chart.defaults.color = b3Color;
    // Override already rendered chart colors.
    for (let instancesKey in Chart.instances) {
        let chart = Chart.instances[instancesKey];
        /** @ts-ignore */
        chart.config.options.backgroundColor = b3Color;
        /** @ts-ignore */
        chart.config.options.color = bcColor;
        // Override dataset colors
        for (let datasetKey in chart.config.data.datasets) {
            let datasetIntKey = parseInt(datasetKey);
            let dataset = chart.config.data.datasets[datasetIntKey];
            dataset.backgroundColor = dataSetColors[datasetIntKey % dataSetColors.length];
            if (dataset.borderColor) {
                dataset.borderColor = dataSetColors[datasetIntKey % dataSetColors.length];
            }
            /** @ts-ignore */
            if (dataset.color) {
                /** @ts-ignore */
                dataset.color = dataSetColors[datasetIntKey % dataSetColors.length];
            }
        }
        bcColorRaw.alpha = 0.5;
        /** @ts-ignore */
        if (chart.scales['x'].options.title) {
            /** @ts-ignore */
            chart.scales['x'].options.title.color = bcColor;
        }
        /** @ts-ignore */
        if (chart.scales['x'].options.ticks) {
            /** @ts-ignore */
            chart.scales['x'].options.ticks.color = bcColorRaw.toRgba();
        }
        /** @ts-ignore */
        if (chart.scales['x'].options.grid) {
            /** @ts-ignore */
            chart.scales['x'].options.grid.color = bcColorRaw.toRgba();
        }
        /** @ts-ignore */
        if (chart.scales['x'].options.border) {
            /** @ts-ignore */
            chart.scales['x'].options.border.color = bcColorRaw.toRgba();
        }
        /** @ts-ignore */
        if (chart.scales['y'].options.title) {
            /** @ts-ignore */
            chart.scales['y'].options.title.color = bcColor;
        }
        /** @ts-ignore */
        if (chart.scales['y'].options.ticks) {
            /** @ts-ignore */
            chart.scales['y'].options.ticks.color = bcColorRaw.toRgba();
        }
        /** @ts-ignore */
        if (chart.scales['y'].options.grid) {
            /** @ts-ignore */
            chart.scales['y'].options.grid.color = bcColorRaw.toRgba();
        }
        /** @ts-ignore */
        if (chart.scales['y'].options.border) {
            /** @ts-ignore */
            chart.scales['y'].options.border.color = bcColorRaw.toRgba();
        }
        chart.update();
    }
}
window.changeTheme = changeTheme;
window.addEventListener('load', function () {
    // get the saved theme from localstorage
    let theme = localStorage.getItem('theme');
    if (theme) {
        changeTheme(theme);
    }
});
//# sourceMappingURL=themes.js.map