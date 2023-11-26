<?php

namespace App\Widgets\Chart;

use Override;

class BubbleChartDataEntry extends ScatterChartDataEntry
{
    public int $r;

    #[Override] public function __construct(int $x, int $y, int $r)
    {
        parent::__construct($x, $y);
        $this->r = $r;
    }
}
