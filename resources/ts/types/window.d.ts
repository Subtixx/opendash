// Extend window interface
import Chart from 'chart.js/auto';
import {GridStack} from 'gridstack';
import {WidgetStack} from './dashboard';
import {Alpine} from '../../vendor/livewire/livewire/dist/livewire.esm';

declare global {
    interface Window {
        Chart: typeof Chart;
        GridStack: typeof GridStack;
        Alpine: typeof Alpine;
        WidgetStack: typeof WidgetStack;

        changeTheme: (theme: string) => void;
    }
}

declare var window: Window;
