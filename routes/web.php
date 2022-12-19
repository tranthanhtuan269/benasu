<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SiteConfigController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RewardController;

$params = [];
$conf = ['prefix' => '', 'where' => []];

Route::get('/referal={code}', function ($code) {
    return redirect('/?referal=' . $code);
})->name('referr');

Route::get('logout', '\App\Http\Controllers\HomeController@logout');

if( env( 'SHOP_MULTILOCALE' ) )
{
    $conf['prefix'] .= '{locale}';
    $conf['where']['locale'] = '[a-z]{2}(\_[A-Z]{2})?';
    $params = ['locale' => app()->getLocale()];

    Route::get('/admin', function () use ($params) {
        return redirect(airoute('aimeos_shop_admin', $params));
    });
}

if( env( 'SHOP_MULTISHOP' ) )
{
    $conf['prefix'] .= '/{site}';
    $conf['where']['site'] = '[A-Za-z0-9\.\-]+';
}

if( $conf['prefix'] )
{
    Route::get('/', function () use ($params) {
        return redirect(airoute('aimeos_home', $params));
    });
}

Route::group($conf ?? [], function() {
    require __DIR__.'/auth.php';
});

Route::post('upload_image', [BlogController::class, 'uploadImage'])->name('upload');
Route::post('upload-crop-image', [BlogController::class, 'uploadCropImage'])->name('uploadCrop');
Route::prefix('admin')->group(function () {
    Route::get('orders/{id}/reward', [OrderController::class, 'reward'])->name('reward');
    Route::resource('blogs', BlogController::class);
    Route::resource('configs', SiteConfigController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('rewards', RewardController::class);
});

Route::get('/blog', [BlogController::class, 'indexf'])->name('blogs.indexf');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');

Route::resource('comments', CommentController::class);
Route::resource('pages', PageController::class);
Route::get('pages/{slug}', [PageController::class, 'slug'])->name('pages.show');
Route::get('favorite', [HomeController::class, 'indexAction'])->name('favorite');
Route::get('test', [HomeController::class, 'test'])->name('test');
Route::post('users/refer', [HomeController::class, 'referAction'])->name('refer');
Route::post('users/create-coupon', [HomeController::class, 'createCoupon'])->name('createCoupon');