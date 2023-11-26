<?php

namespace App\Widgets\Chart;

class ChartDataset
{
    public string $label;
    public string $backgroundColor;
    public string $borderColor;
    public int $borderWidth;
    public string $hoverBackgroundColor;
    public string $hoverBorderColor;
    public array $data;

    /**
     * @param string $label
     * @param array $data
     */
    public function __construct(string $label, array $data)
    {
        $this->label = $label;
        $this->data = $data;
    }
}
