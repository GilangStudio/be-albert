<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Shop\UserController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\VoucherController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\PressController;
use App\Http\Controllers\Website\BannerController;
use App\Http\Controllers\Shop\ShopBannerController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Shop\ProductSizeController;
use App\Http\Controllers\Shop\MainCategoryController;
use App\Http\Controllers\Shop\ProductImageController;
use App\Http\Controllers\Website\CollectionController;
use App\Http\Controllers\Website\AchievementController;
use App\Http\Controllers\Shop\ProductCategoryController;
use App\Http\Controllers\Website\AboutCompanyController;
use App\Http\Controllers\Shop\FashionWeekBannerController;
use App\Http\Controllers\Website\WebsiteSettingsController;
use App\Http\Controllers\Website\CollectionBannerController;
use App\Http\Controllers\Website\CollectionGenderController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

//route grouping
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('layouts.main');
    })->name('dashboard');


    Route::group(['prefix' => 'website'], function () {
        Route::resource('/banner', BannerController::class)->names('banner');
        Route::post('/banner/sort', [BannerController::class, 'sort'])->name('banner.sort');

        Route::resource('/collection', CollectionController::class);
        Route::post('/collection/{collection}/images', [CollectionGenderController::class, 'update'])->name('collection.images');
        Route::delete('/collection/{collection}/image/{id}/delete', [CollectionGenderController::class, 'destroy'])->name('collection.image.delete');

        // Collection Banner Routes - Tambahkan ini
        Route::group(['prefix' => 'collection-banner'], function () {
            // Collection Banner
            Route::get('/collection', [CollectionBannerController::class, 'collectionIndex'])->name('collection-banner.collection.index');
            Route::post('/collection', [CollectionBannerController::class, 'collectionUpdate'])->name('collection-banner.collection.update');
            Route::delete('/collection/image/{id}', [CollectionBannerController::class, 'collectionImageDestroy'])->name('collection-banner.collection.image.destroy');
            Route::post('/collection/sort', [CollectionBannerController::class, 'collectionSort'])->name('collection-banner.collection.sort');
            
            // Bridal Banner
            Route::get('/bridal', [CollectionBannerController::class, 'bridalIndex'])->name('collection-banner.bridal.index');
            Route::post('/bridal', [CollectionBannerController::class, 'bridalUpdate'])->name('collection-banner.bridal.update');
            Route::delete('/bridal/image/{id}', [CollectionBannerController::class, 'bridalImageDestroy'])->name('collection-banner.bridal.image.destroy');
            Route::post('/bridal/sort', [CollectionBannerController::class, 'bridalSort'])->name('collection-banner.bridal.sort');
        });
        
        Route::resource('/press', PressController::class);
        Route::resource('/about', AboutController::class);

        Route::resource('/achievement', AchievementController::class);
        Route::post('/achievement/sort', [AchievementController::class, 'sort'])->name('achievement.sort');

        Route::get('/about-company', [AboutCompanyController::class, 'index'])->name('about-company.index');
        Route::post('/about-company', [AboutCompanyController::class, 'update'])->name('about-company.update');

        Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
        Route::post('/contact', [ContactController::class, 'update'])->name('contact.update');
        Route::get('/settings', [WebsiteSettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [WebsiteSettingsController::class, 'update'])->name('settings.update');
    });

    Route::group(['prefix' => 'shop'], function () {
        Route::resource('/banner', ShopBannerController::class)->names('shop.banner');
        Route::post('/banner/sort', [ShopBannerController::class, 'sort'])->name('shop.banner.sort');
        Route::resource('/fashion-week-banner', FashionWeekBannerController::class)->names('shop.fashion-week-banner');
        Route::post('/fashion-week-banner/sort', [FashionWeekBannerController::class, 'sort'])->name('shop.fashion-week-banner.sort');
        Route::resource('/main-category', MainCategoryController::class);
        Route::resource('/product-category', ProductCategoryController::class);
        Route::post('/product-category/get', [ProductCategoryController::class, 'get'])->name('product-category.get');

        Route::get('/vouchers/special', [VoucherController::class, 'vouchers_special'])->name('vouchers.special');
        Route::post('/vouchers/special', [VoucherController::class, 'vouchers_special_update']);

        Route::resource('/vouchers', VoucherController::class);

        // Route::post('/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('/users', UserController::class);

        Route::resource('/product', ProductController::class);
        Route::delete('/product/{product}/image/{id}/delete', [ProductImageController::class, 'destroy'])->name('product.image.delete');
        Route::delete('/product/{product}/size/{id}/delete', [ProductSizeController::class, 'destroy'])->name('product.size.delete');

        Route::resource('/orders', OrderController::class);
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'index']);
});

require __DIR__.'/auth.php';
