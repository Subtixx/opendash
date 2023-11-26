<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use App\Widgets\ChartWidget;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $widgets = Widget::all();

        foreach ($widgets as $widget) {
            $widget->widget = new $widget->widget_class($widget->arguments ?? []);
            $widget->widget->process();
            $widget->constraints = $widget->widget->getConstraints();
            $widget->canResize = $widget->widget->canResize();
        }
        return view('dashboard', compact('widgets'));
    }

    public function widget()
    {
        $widget = new ChartWidget();
        $widget->process();
        return view('widget', compact('widget'));
    }

    /** XHR REQUESTS */
    public function saveWidget(Request $request): JsonResponse
    {
        $widget = Widget::find($request->id);
        $widget->x = $request->x;
        $widget->y = $request->y;
        $widget->width = $request->width;
        $widget->height = $request->height;
        $widget->save();
        return response()->json(['success' => true]);
    }

    public function saveWidgets(Request $request): JsonResponse
    {
        $widgets = $request->get('widgets');
        if (!$widgets) {
            return response()->json(['success' => false], 400);
        }

        foreach ($widgets as $widget) {
            if (!array_key_exists('id', $widget) ||
                !array_key_exists('x', $widget) ||
                !array_key_exists('y', $widget) ||
                !array_key_exists('width', $widget) ||
                !array_key_exists('height', $widget)) {
                return response()->json(['success' => false], 400);
            }
            $dbWidget = Widget::find($widget['id']);
            if (!$dbWidget) {
                return response()->json(['success' => false], 400);
            }

            if ($dbWidget->x !== (int)$widget['x']) {
                $dbWidget->x = (int)$widget['x'];
            }
            if ($dbWidget->y !== (int)$widget['y']) {
                $dbWidget->y = (int)$widget['y'];
            }
            if ($dbWidget->width !== (int)$widget['width']) {
                $dbWidget->width = (int)$widget['width'];
            }
            if ($dbWidget->height !== (int)$widget['height']) {
                $dbWidget->height = (int)$widget['height'];
            }

            if(!$dbWidget->isDirty()) {
                continue;
            }

            if(!$dbWidget->save()) {
                return response()->json(['success' => false], 500);
            }
        }
        return response()->json(['success' => true]);
    }
}
