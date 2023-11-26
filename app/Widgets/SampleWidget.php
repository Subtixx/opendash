<?php

namespace App\Widgets;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Override;

class SampleWidget extends Widget
{
    #[Override] public function render(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        return view('widgets.sample_widget');
    }

    #[Override] public function hasSettings(): bool
    {
        return true;
    }
}
