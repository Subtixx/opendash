<?php

namespace App\Widgets;

use App\Widgets\Chart\BubbleChartDataEntry;
use App\Widgets\Chart\ChartData;
use App\Widgets\Chart\ChartDataset;
use App\Widgets\Chart\ScatterChartDataEntry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Override;

class ChartWidget extends Widget
{
    public ChartData $chart;

    #[Override] public function process(): bool
    {
        $type = array_key_exists('type', $this->arguments) ? $this->arguments['type'] : 'line';
        $datasets = [];
        $labels = [];
        if (array_key_exists('datasets', $this->arguments)) {
            foreach ($this->arguments['datasets'] as $dataset) {
                $datasets[] = new ChartDataset(
                    $dataset['label'],
                    array_map(static function ($entry) use ($type) {
                        return match ($type) {
                            'bubble' => new BubbleChartDataEntry($entry['x'], $entry['y'], $entry['r']),
                            'scatter' => new ScatterChartDataEntry($entry['x'], $entry['y']),
                            default => $entry,
                        };
                    }, $dataset['data'])
                );
            }
        }

        if (array_key_exists('labels', $this->arguments) && is_array($this->arguments['labels'])) {
            $labels = $this->arguments['labels'];
        }
        $this->chart = new ChartData(
            $labels,
            $datasets,
            $type
        );
        return true;
    }


    #[Override] public function render(): View|\Illuminate\Foundation\Application|Factory|Application|string
    {
        return view('widgets.chart-widget', [
            'widget' => $this,
            'chart' => $this->chart,
        ]);
    }

    #[Override] public function hasSettings(): bool
    {
        return true;
    }
}
