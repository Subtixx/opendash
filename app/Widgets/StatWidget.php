<?php

namespace App\Widgets;

use App\Widgets\Widget;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JetBrains\PhpStorm\ArrayShape;
use Override;

class StatWidget extends Widget
{
    protected const COLOR_NONE = 0;
    protected const COLOR_PRIMARY = 1;
    protected const COLOR_SECONDARY = 2;
    protected const COLOR_SUCCESS = 3;
    protected const COLOR_DANGER = 4;
    protected const COLOR_WARNING = 5;
    protected const COLOR_INFO = 6;

    #[Override] public function render(): View|\Illuminate\Foundation\Application|Factory|Application|string
    {
        return view('widgets.stat_widget', [
            'title' => $this->getTitle(),
            'icon' => $this->getIcon(),
            'value' => $this->getValue(),
            'color' => $this->getColorClass(),
        ]);
    }

    #[Override] #[ArrayShape([
        'max_width' => 'int',
        'max_height' => 'int',
        'min_width' => 'int',
        'min_height' => 'int'
    ])] public function getConstraints(): array
    {
        return [
            'max_width' => 2,
            'max_height' => 1,
            'min_width' => 2,
            'min_height' => 1,
        ];
    }

    protected function getTitle(): string
    {
        return "Stat Widget";
    }

    protected function getIcon(): string
    {
        return "heroicon-o-chart-pie";
    }

    protected function getValue(): string
    {
        return "0";
    }

    protected function getColor(): int
    {
        return self::COLOR_NONE;
    }

    private function getColorClass(): string
    {
        switch ($this->getColor()) {
            default:
            case self::COLOR_NONE:
                return "";
            case self::COLOR_PRIMARY:
                return "bg-primary text-primary-content";
            case self::COLOR_SECONDARY:
                return "bg-secondary text-secondary-content";
            case self::COLOR_SUCCESS:
                return "bg-success text-success-content";
            case self::COLOR_DANGER:
                return "bg-danger text-danger-content";
            case self::COLOR_WARNING:
                return "bg-warning text-warning-content";
            case self::COLOR_INFO:
                return "bg-info text-info-content";
        }
    }

    #[Override] public function canResize(): bool
    {
        return false;
    }
}
