<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Widget;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', static function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/dashboard/save', [DashboardController::class, 'saveWidgets'])->middleware([
    'auth',
    'verified'
])->name('dashboard.save');
Route::get('/widget', [DashboardController::class, 'widget'])->middleware(['auth', 'verified'])->name('widget');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(
    [
        'prefix' => 'xhr/v1',
        'namespace' => 'Api\v1',
        'as' => 'api.'
    ],
    static function () {
        Route::get('/dashboard/widgets/{id}', static function ($id) {
            $widgets = Widget::find($id);
            $widget = new $widgets->widget_class($widgets->arguments ?? []);
            $widget->process();
            return $widget->render();

            /*foreach ($widgets as $widget) {
                $widget->widget = new $widget->widget_class($widget->arguments ?? []);
                $widget->widget->process();
                $widget->constraints = $widget->widget->getConstraints();
                $widget->canResize = $widget->widget->canResize();
            }
            return '<div class="bg-base-100 p-4 rounded-box h-full">' . date('Y-m-d H:i:s') . ' Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>';*/
        });
    }
);

require __DIR__ . '/auth.php';
