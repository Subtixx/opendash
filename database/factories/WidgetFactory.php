<?php

namespace Database\Factories;

use App\Models\Widget;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Override;
use App\Widgets;

class WidgetFactory extends Factory
{
    protected $model = Widget::class;

    #[Override] public function definition(): array
    {
        // Get all the classes in app\Widgets folder
        $widgetClasses = collect(glob(app_path('Widgets/*.php')))
            ->map(static function ($file) {
                $class = 'App\\Widgets\\' . basename($file, '.php');
                return new $class();
            })
            ->filter(static function ($class) {
                return $class instanceof Widgets\Widget;
            })
            ->filter(static function ($class) {
                return $class::class !== Widgets\Widget::class;
            });
        $widgetClass = $widgetClasses->random()::class;
        return [
            'name' => $this->faker->name(),
            'x' => $this->faker->numberBetween(0, 10),
            'y' => $this->faker->numberBetween(0, 10),
            'width' => $this->faker->numberBetween(2, 4),
            'height' => $this->faker->numberBetween(2, 4),
            'widget_class' => $widgetClass,
            'arguments' => [],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
