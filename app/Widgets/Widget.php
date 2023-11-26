<?php

namespace App\Widgets;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JetBrains\PhpStorm\ArrayShape;

class Widget
{
    protected array $arguments;

    public function __construct(array $arguments = [])
    {
        $this->arguments = $arguments;
    }

    public function process(): bool
    {
        return true;
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|Application|string
    {
        return "";
    }

    public function hasSettings(): bool
    {
        return false;
    }

    /**
     * Returns the constraints of the widget.
     * @return array{max_width: int, max_height: int, min_width: int, min_height: int}
     */
    #[ArrayShape([
        'max_width' => 'int',
        'max_height' => 'int',
        'min_width' => 'int',
        'min_height' => 'int'
    ])] public function getConstraints(): array
    {
        return [
            'max_width' => 6,
            'max_height' => 6,
            'min_width' => 1,
            'min_height' => 1,
        ];
    }

    public function canResize() : bool
    {
        return true;
    }
}
