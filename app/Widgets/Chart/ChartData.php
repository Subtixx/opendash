<?php

namespace App\Widgets\Chart;

use InvalidArgumentException;
use JsonException;

class ChartData
{
    public array $labels;
    public array $datasets;
    private string $type = 'line';

    public const TYPE_LINE = 'line';
    public const TYPE_BAR = 'bar';
    public const TYPE_RADAR = 'radar';
    public const TYPE_DOUGHNUT = 'doughnut';
    public const TYPE_PIE = 'pie';
    public const TYPE_POLAR_AREA = 'polarArea';
    public const TYPE_BUBBLE = 'bubble';
    public const TYPE_SCATTER = 'scatter';

    /**
     * @param array $labels
     * @param array $datasets
     * @param string $type
     */
    public function __construct(array $labels, array $datasets, string $type = 'line')
    {
        $this->setType($type);
        $this->labels = $labels;
        $this->setDatasets($datasets);
    }

    public function addDataset(ChartDataset $dataset): void
    {
        if ($this->type === self::TYPE_BUBBLE || $this->type === self::TYPE_SCATTER) {
            foreach ($dataset->data as $data) {
                if (($this->type === self::TYPE_SCATTER && !($data instanceof ScatterChartDataEntry)) ||
                    ($this->type === self::TYPE_BUBBLE && !($data instanceof BubbleChartDataEntry))) {
                    throw new InvalidArgumentException(
                        'Bubble and scatter charts require data to be an array of' .
                        ' ScatterChartDataEntry or BubbleChartDataEntry objects');
                }
                if (!is_int($data->x) || !is_int($data->y)) {
                    throw new InvalidArgumentException('Bubble and scatter charts require x and y to be integers');
                }
                if ($this->type === self::TYPE_BUBBLE && !is_int($data->r)) {
                    throw new InvalidArgumentException('Bubble charts require r to be an integer');
                }
            }
        }
        $this->datasets[] = $dataset;
    }

    public function setDatasets(array $datasets): void
    {
        $this->datasets = [];
        foreach ($datasets as $dataset) {
            $this->addDataset($dataset);
        }
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        // this needs to output ' instead of " for the json to be valid
        return str_replace('"', "'",
            json_encode([
                'type' => $this->type,
                'data' => [
                    'labels' => $this->labels,
                    'datasets' => $this->datasets,
                ],
                'options' => [
                    'responsive' => true,
                ],
            ], JSON_THROW_ON_ERROR));
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        if (!in_array($type, [
            self::TYPE_LINE,
            self::TYPE_BAR,
            self::TYPE_RADAR,
            self::TYPE_DOUGHNUT,
            self::TYPE_PIE,
            self::TYPE_POLAR_AREA,
            self::TYPE_BUBBLE,
            self::TYPE_SCATTER,
        ])) {
            throw new InvalidArgumentException('Invalid chart type');
        }

        $this->type = $type;
    }
}
