import './bootstrap';
// @ts-ignore
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Tooltip from "@ryangjchandler/alpine-tooltip";
import 'moment';
import 'luxon';
import 'date-fns';
import Chart from 'chart.js/auto';
import zoomPlugin from 'chartjs-plugin-zoom';
import annotationPlugin from 'chartjs-plugin-annotation';
import 'chartjs-adapter-moment';
import 'chartjs-adapter-luxon';
import 'chartjs-adapter-date-fns';
import { MatrixController, MatrixElement } from 'chartjs-chart-matrix';
import { GridStack } from 'gridstack';
Chart.register(annotationPlugin);
Chart.register(MatrixController, MatrixElement);
Chart.register(zoomPlugin);
window.Chart = Chart;
window.GridStack = GridStack;
Alpine.plugin(Tooltip);
window.Alpine = Alpine;
//window.Alpine.start();
import { WidgetStack } from "./dashboard";
window.WidgetStack = WidgetStack;
import './themes';
Livewire.start();
//# sourceMappingURL=app.js.map