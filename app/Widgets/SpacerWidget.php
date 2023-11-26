<?php

namespace App\Widgets;

use App\Widgets\Widget;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Override;

class SpacerWidget extends Widget
{
    #[Override] public function render(): View|\Illuminate\Foundation\Application|Factory|Application|string
    {
        return "";
    }

}
