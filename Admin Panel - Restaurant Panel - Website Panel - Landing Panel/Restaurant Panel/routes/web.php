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

Route::get('lang/change', [App\Http\Controllers\LangController::class, 'change'])->name('changeLang');

Route::post('setToken', [App\Http\Controllers\Auth\AjaxController::class, 'setToken'])->name('setToken');

Route::get('register', function () {
    return view('auth.register');
})->name('register');

Route::get('register/phone', function () {
    return view('auth.phone_register');
})->name('register.phone');


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::get('/users/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('user.profile');

Route::get('/foods', [App\Http\Controllers\FoodController::class, 'index'])->name('foods');

Route::get('/foods/edit/{id}', [App\Http\Controllers\FoodController::class, 'edit'])->name('foods.edit');

Route::get('/foods/create', [App\Http\Controllers\FoodController::class, 'create'])->name('foods.create');

Route::get('/orders', [App\Http\Controllers\OrderController::class, 'index'])->name('orders');

Route::get('/orders/edit/{id}', [App\Http\Controllers\OrderController::class, 'edit'])->name('orders.edit');

Route::get('/placedOrders', [App\Http\Controllers\OrderController::class, 'placedOrders'])->name('placedOrders');

Route::get('/acceptedOrders', [App\Http\Controllers\OrderController::class, 'acceptedOrders'])->name('acceptedOrders');

Route::get('/rejectedOrders', [App\Http\Controllers\OrderController::class, 'rejectedOrders'])->name('rejectedOrders');

Route::get('/payments', [App\Http\Controllers\PayoutsController::class, 'index'])->name('payments');

Route::get('/payments/create', [App\Http\Controllers\PayoutsController::class, 'create'])->name('payments.create');

Route::get('/payments/edit/{id}', [App\Http\Controllers\PaymentController::class, 'edit'])->name('payments.edit');

Route::get('/earnings', [App\Http\Controllers\EarningController::class, 'index'])->name('earnings');

Route::get('/earnings/edit/{id}', [App\Http\Controllers\EarningController::class, 'edit'])->name('earnings.edit');

Route::get('/coupons', [App\Http\Controllers\CouponController::class, 'index'])->name('coupons');

Route::get('/coupons/edit/{id}', [App\Http\Controllers\CouponController::class, 'edit'])->name('coupons.edit');

Route::get('/coupons/create', [App\Http\Controllers\CouponController::class, 'create'])->name('coupons.create');

Route::post('order-status-notification', [App\Http\Controllers\OrderController::class, 'sendNotification'])->name('order-status-notification');

Route::post('/sendnotification', [App\Http\Controllers\BookTableController::class, 'sendnotification'])->name('sendnotification');

Route::get('/booktable', [App\Http\Controllers\BookTableController::class, 'index'])->name('booktable');

Route::get('/booktable/edit/{id}', [App\Http\Controllers\BookTableController::class, 'edit'])->name('booktable.edit');

Route::get('/orders/print/{id}', [App\Http\Controllers\OrderController::class, 'orderprint'])->name('vendors.orderprint');
Route::get('/wallettransaction', [App\Http\Controllers\TransactionController::class, 'index'])->name('wallettransaction.index');

Route::post('send-email', [App\Http\Controllers\SendEmailController::class, 'sendMail'])->name('sendMail');

Route::get('forgot-password', [App\Http\Controllers\Auth\LoginController::class, 'forgotPassword'])->name('forgot-password');
Route::get('document-list', [App\Http\Controllers\DocumentController::class, 'DocumentList'])->name('vendors.document');
Route::get('document/upload/{id}', [App\Http\Controllers\DocumentController::class, 'DocumentUpload'])->name('document.upload');

Route::get('withdraw-method', [App\Http\Controllers\WithdrawMethodController::class, 'index'])->name('withdraw-method');
Route::get('withdraw-method/add', [App\Http\Controllers\WithdrawMethodController::class, 'create'])->name('withdraw-method.create');

Route::post('store-firebase-service', [App\Http\Controllers\HomeController::class,'storeFirebaseService'])->name('store-firebase-service');
