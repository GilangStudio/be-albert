<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Shop\AuthController;
use App\Http\Controllers\API\Shop\CartController;
use App\Http\Controllers\API\Shop\OrderController;
use App\Http\Controllers\API\Shop\FilterController;
use App\Http\Controllers\API\Website\ApiController;
use App\Http\Controllers\API\XenditIncomingRequest;
use App\Http\Controllers\API\Shop\AddressController;
use App\Http\Controllers\API\Shop\ProductController;
use App\Http\Controllers\API\Shop\VoucherController;
use App\Http\Controllers\API\Shop\CheckoutController;
use App\Http\Controllers\API\Shop\HomeController;
use App\Http\Controllers\API\Shop\WishlistController;
use App\Http\Controllers\FashionWeekBannerController;
use App\Http\Controllers\API\Shop\MainCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::match(['get', 'post'], '/xendit/invoices_status', [XenditIncomingRequest::class, 'invoicesStatus']);
Route::match(['get', 'post'], '/xendit/payment_expired', [XenditIncomingRequest::class, 'paymentExpired']);

Route::group(['prefix' => 'website'], function () {
    Route::get('/settings/get', [ApiController::class, 'get_settings']);

    Route::get('/home/get', [ApiController::class, 'get_home']);
    Route::get('/about/get', [ApiController::class, 'get_about']);
    Route::get('/press/get', [ApiController::class, 'get_press']);
    Route::get('/contact/get', [ApiController::class, 'get_contact']);
    Route::get('/collection/get', [ApiController::class, 'get_collection']);
    Route::get('/collection/bridal/get', [ApiController::class, 'get_collection_bridal']);
    Route::get('/collection/{slug}/get', [ApiController::class, 'get_collection_detail']);
    
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/reset-password', [AuthController::class, 'reset_password']);
Route::post('/change-password/{token}', [AuthController::class, 'change_password']);

Route::post('/validate-token', [AuthController::class, 'validate_token']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'get'], function () {
        Route::post('/provinces', [AddressController::class, 'getProvince']);
        Route::post('/cities', [AddressController::class, 'getCity']);
        Route::post('/districts', [AddressController::class, 'getDistrict']);
        Route::post('/subdistricts', [AddressController::class, 'getSubdistrict']);
    });

    Route::get('/account', [AuthController::class, 'account']);
    Route::post('/account', [AuthController::class, 'accountUpdate']);
    Route::post('/account/password', [AuthController::class, 'accountPassword']);
    Route::post('/account/delete', [AuthController::class, 'deleteAccount']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/address/{id}/get', [AddressController::class, 'getAddressDetail']);
    Route::get('/address/get', [AddressController::class, 'getAddress']);
    Route::post('/address/add', [AddressController::class, 'addAddress']);
    Route::post('/address/{id}/update', [AddressController::class, 'updateAddress']);
    Route::post('/address/set_primary', [AddressController::class, 'setPrimaryAddress']);
    Route::post('/address/remove', [AddressController::class, 'removeAddress']);

    Route::get('/cart/get', [CartController::class, 'cartGet']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/update', [CartController::class, 'updateCart']);
    Route::post('/cart/remove', [CartController::class, 'removeCart']);
    Route::get('/cart/total/get', [CartController::class, 'getTotalCart']);
    Route::get('/wishlist/get', [WishlistController::class, 'getWishlist']);
    Route::post('/wishlist/remove', [WishlistController::class, 'removeWishlist']);
    Route::get('/wishlist/total/get', [WishlistController::class, 'getTotalWishlist']);
    Route::get('/product/{slug}/wishlist', [WishlistController::class, 'getWishlistUserProduct']);
    Route::post('/product/wishlist', [WishlistController::class, 'wishlist']);

    Route::post('/voucher/apply', [VoucherController::class, 'applyVoucher']);
    Route::get('/voucher/get', [VoucherController::class, 'getVoucher']);

    Route::get('/checkout/get', [CheckoutController::class, 'getCheckout']);
    
    Route::post('/checkout/place_order', [CheckoutController::class, 'placeOrder']);

    Route::get('/orders/get', [OrderController::class, 'getOrders']);
});

Route::get('/home/get', [HomeController::class, 'getHome']);
Route::get('/product/search', [ProductController::class, 'searchProducts']);
Route::get('/product/get', [ProductController::class, 'getAllProducts']);
Route::get('/product/{slug}/get', [ProductController::class, 'getProductBySlug']);
Route::get('/filter/{slug}/get', [FilterController::class, 'get']);


Route::get('/collection/get', [MainCategoryController::class, 'get']);
Route::get('/collection/{slug}/product/get', [MainCategoryController::class, 'getProduct']);
Route::post('/collection/{slug}/product/filter', [FilterController::class, 'filter']);