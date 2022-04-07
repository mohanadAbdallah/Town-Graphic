<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['localization', 'auth:sanctum', 'throttle:api'], 'as' => 'api.'], function () {


    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        /* one route calls */
        Route::get('/profile', [UserController::class, 'showUserProfile'])->name('profile');
        Route::post('/profile', [UserController::class, 'updateUserProfile'])->name('profile.update');
        Route::post('/password/update', [UserController::class, 'updatePassword'])->name('password.update');
        Route::post('/password/forgot/update', [UserController::class, 'updateForgotPassword'])->name('forgot.password.update');

    });
    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        /* one route calls */
        Route::get('/', [CategoryController::class, 'showCategories'])->name('all');
        Route::get('/{category}', [CategoryController::class, 'showSubCategories'])->name('subcategories');
        Route::get('/agent/{agent_id}', [CategoryController::class, 'showAgentCategories'])->name('agent.subcategories');
        Route::get('/subcategory/{subcategory}/products', [ProductController::class, 'showProducts'])->name('subcategories.products');
        Route::get('/{category}/subcategories/all/products', [ProductController::class, 'showAllProducts'])->name('products.all');

    });
    Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {
        /* one route calls */
        Route::get('/', [CartController::class, 'showListUserCart'])->name('all');
        Route::get('/current', [CartController::class, 'showUserCart'])->name('show');
        Route::post('/', [CartController::class, 'makeUserCart'])->name('store');
        Route::post('/item/{cart_item_id}/update', [CartController::class, 'updateCartItem'])->name('update');
        Route::delete('/{id}', [CartController::class, 'removeCartItem'])->name('destroy');
        Route::post('/{id}/delivery/location', [CartController::class, 'setCartDeliveryLocation'])->name('delivery.location');
        Route::post('/{id}/pay/cash', [OrderController::class, 'makeCashOrder'])->name('cash');
        Route::post('/{id}/pay/paypal', [OrderController::class, 'makePayPalOrder'])->name('paypal');

    });
    Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
        /* one route calls */
        Route::get('/', [OrderController::class, 'showUserOrders'])->name('all');
        Route::get('/{id}', [OrderController::class, 'showOrder'])->name('show');
        Route::post('/{id}/rate', [OrderController::class, 'makeOrderRating'])->name('rate');

    });


});

/* Start General routes */
Route::group(['as' => 'api.', 'middleware' => ['localization', 'throttle:api']], function () {
    /* one route calls */
    Route::post('/user/register', [RegisterController::class, 'register'])->name('user.register');
    Route::post('/user/login', [LoginController::class, 'login'])->name('user.login');
    Route::post('/user/otp/send', [UserController::class, 'sendOtpCode'])->name('user.otp.send');
    Route::post('/user/otp/verify', [UserController::class, 'verifyOtpCode'])->name('user.otp.verify');

    Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
        /* one route calls */
        Route::get('/about_us', [SettingController::class, 'showAboutUs'])->name('about_us');
        Route::post('/contact_us', [SettingController::class, 'storeContactUs'])->name('contact_us');
        Route::get('/advertisements', [SettingController::class, 'getAdvertisement'])->name('advertisements');

    });

    /* redirect not logged-in users to unauthenticated route */
    Route::get('/unauthenticated', function () {
        return response()->json(['message' => 'unauthenticated', 'status' => false], 401);
    })->name('unauthenticated');
});
/* End General routes */

