<?php

use App\Http\Controllers\AllRestaurantsController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TermsController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('set-location', [App\Http\Controllers\HomeController::class, 'setLocation'])->name('set-location');

Route::get('login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');

Route::get('signup', [App\Http\Controllers\LoginController::class, 'signup'])->name('signup');

Route::get('search', [App\Http\Controllers\SearchController::class, 'index'])->name('search');

Route::get('lang/change', [App\Http\Controllers\LangController::class, 'change'])->name('changeLang');

Route::get('privacy', [App\Http\Controllers\CmsController::class, 'privacypolicy'])->name('privacy');

Route::get('terms', [App\Http\Controllers\CmsController::class, 'termsofuse'])->name('terms');

Route::get('deliveryofsupport', [App\Http\Controllers\CmsController::class, 'deliveryofsupport'])->name('deliveryofsupport');

Route::post('takeaway', [App\Http\Controllers\PaymentController::class, 'takeawayOption'])->name('takeaway');

Route::get('my_order', [App\Http\Controllers\OrderController::class, 'index'])->name('my_order');

Route::get('completed_order', [App\Http\Controllers\OrderController::class, 'completedOrders'])->name('completed_order');

Route::get('pending_order', [App\Http\Controllers\OrderController::class, 'pendingOrder'])->name('pending_order');

Route::get('cancelled_order', [App\Http\Controllers\OrderController::class, 'cancelledOrder'])->name('cancelled_order');

Route::get('my_dinein', [App\Http\Controllers\OrderController::class, 'myDinein'])->name('my_dinein');

Route::get('dinein', [App\Http\Controllers\OrderController::class, 'dinein'])->name('dinein');

Route::get('contact-us', [App\Http\Controllers\ContactUsController::class, 'index'])->name('contact_us');

Route::get('trending', [App\Http\Controllers\TrendingController::class, 'index'])->name('trending');

Route::get('categories', [App\Http\Controllers\RestaurantController::class, 'categoryList'])->name('categorylist');

Route::get('category/{id}', [App\Http\Controllers\RestaurantController::class, 'categoryDetail'])->name('category_detail');

Route::get('restaurant', [App\Http\Controllers\RestaurantController::class, 'index'])->name('restaurant');

Route::get('cart', [App\Http\Controllers\ProductController::class, 'cart'])->name('cart');

Route::post('add-to-cart', [App\Http\Controllers\ProductController::class, 'addToCart'])->name('add-to-cart');

Route::post('reorder-add-to-cart', [App\Http\Controllers\ProductController::class, 'reorderaddToCart'])->name('reorder-add-to-cart');

Route::get('products', [App\Http\Controllers\ProductController::class, 'productListAll'])->name('productlist.all');

Route::get('product/{id}', [App\Http\Controllers\ProductController::class, 'productDetail'])->name('productDetail');

Route::get('products/{type}/{id}', [App\Http\Controllers\ProductController::class, 'productList'])->name('productList');

Route::post('update-cart', [App\Http\Controllers\ProductController::class, 'update'])->name('update-cart');

Route::post('remove-from-cart', [App\Http\Controllers\ProductController::class, 'remove'])->name('remove-from-cart');

Route::post('change-quantity-cart', [App\Http\Controllers\ProductController::class, 'changeQuantityCart'])->name('change-quantity-cart');

Route::post('apply-coupon', [App\Http\Controllers\ProductController::class, 'applyCoupon'])->name('apply-coupon');

Route::get('checkout', [App\Http\Controllers\CheckoutController::class, 'checkout'])->name('checkout');

Route::post('order-complete', [App\Http\Controllers\ProductController::class, 'orderComplete'])->name('order-complete');

Route::post('order-tip-add', [App\Http\Controllers\ProductController::class, 'orderTipAdd'])->name('order-tip-add');

Route::post('order-delivery-option', [App\Http\Controllers\ProductController::class, 'orderDeliveryOption'])->name('order-delivery-option');

Route::get('pay', [App\Http\Controllers\CheckoutController::class, 'proccesstopay'])->name('pay');

Route::post('order-proccessing', [App\Http\Controllers\CheckoutController::class, 'orderProccessing'])->name('order-proccessing');

Route::post('stripepaymentcallback', [App\Http\Controllers\PaymentController::class, 'stripePaymentcallback'])->name('stripepaymentcallback');

Route::post('process-stripe', [App\Http\Controllers\CheckoutController::class, 'processStripePayment'])->name('process-stripe');

Route::post('process-paypal', [App\Http\Controllers\CheckoutController::class, 'processPaypalPayment'])->name('process-paypal');

Route::post('razorpaypayment', [App\Http\Controllers\CheckoutController::class, 'razorpaypayment'])->name('razorpaypayment');

Route::post('process-mercadopago', [App\Http\Controllers\CheckoutController::class, 'processMercadoPagoPayment'])->name('process-mercadopago');

Route::get('success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('success');

Route::get('failed', [App\Http\Controllers\CheckoutController::class, 'failed'])->name('failed');

Route::get('notify', [App\Http\Controllers\CheckoutController::class, 'notify'])->name('notify');

Route::get('transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');

Route::get('/offers', [App\Http\Controllers\OffersController::class, 'index'])->name('offers');

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

Route::get('favorite-stores', [FavoritesController::class, 'index'])->name('favorites');

Route::get('favorite-products', [FavoritesController::class, 'favProduct'])->name('favorites.product');

Route::get('/faq', [App\Http\Controllers\FaqController::class, 'index'])->name('faq');

Route::get('/restaurants', [App\Http\Controllers\AllRestaurantsController::class, 'index'])->name('restaurants');

Route::get('restaurants/category/{id}', [App\Http\Controllers\AllRestaurantsController::class, 'RestaurantsbyCategory'])->name('RestaurantsbyCategory');

Route::get('/dineinRestaurants', [App\Http\Controllers\DiveinRestaurantController::class, 'index'])->name('dineinRestaurants');

Route::get('/dyiningrestaurant', [App\Http\Controllers\DiveinRestaurantController::class, 'dyiningrestaurant'])->name('dyiningrestaurant');

Route::post('/sendnotification', [App\Http\Controllers\RestaurantController::class, 'sendnotification'])->name('sendnotification');

Route::post('setToken', [App\Http\Controllers\Auth\AjaxController::class, 'setToken'])->name('setToken');

Route::post('logout', [App\Http\Controllers\Auth\AjaxController::class, 'logout'])->name('logout');

Route::post('newRegister', [App\Http\Controllers\Auth\AjaxController::class, 'newRegister'])->name('newRegister');

Route::post('checkEmail', [App\Http\Controllers\Auth\AjaxController::class, 'checkEmail'])->name('checkEmail');

Route::post('sendemail/send', [App\Http\Controllers\SendEmailController::class, 'send'])->name('sendContactUsMail');

Route::get('my_order/{id}', [App\Http\Controllers\OrderController::class, 'edit'])->name('orderDetails');

Route::post('add-cart-note', [App\Http\Controllers\OrderController::class, 'addCartNote'])->name('add-cart-note');

Route::get('proccesspaystack', [App\Http\Controllers\CheckoutController::class, 'proccesspaystack'])->name('proccesspaystack');

Route::get('page/{slug}', [App\Http\Controllers\CmsController::class, 'index'])->name('page');

Route::post('order-schedule-time-add', [App\Http\Controllers\ProductController::class, 'orderScheduleTimeAdd'])->name('order-schedule-time-add');

Route::post('send-email', [App\Http\Controllers\SendEmailController::class, 'sendMail'])->name('sendMail');

Route::get('lang/change', [App\Http\Controllers\LangController::class, 'change'])->name('changeLang');

Route::get('forgot-password', [App\Http\Controllers\LoginController::class, 'forgotPassword'])->name('forgot-password');
Route::get('buy-gift-card', [App\Http\Controllers\GiftCardController::class, 'index'])->name('customize.giftcard');
Route::post('gift-card-processing', [App\Http\Controllers\GiftCardController::class, 'giftCardProcessing'])->name('giftcard.processing');
Route::get('pay-giftcard', [App\Http\Controllers\GiftCardController::class, 'proccesstopay'])->name('giftcard.pay');
Route::get('gift-card-success', [App\Http\Controllers\GiftCardController::class, 'success'])->name('giftcard.success');

Route::get('giftcards', [App\Http\Controllers\GiftCardController::class, 'giftcards'])->name('giftcards');
Route::post('giftcard-razorpaypayment', [App\Http\Controllers\GiftCardController::class, 'razorpaypayment'])->name('giftcard.razorpaypayment');
Route::post('giftcard-stripepayment', [App\Http\Controllers\GiftCardController::class, 'processStripePayment'])->name('giftcard.stripepayment');
Route::post('giftcard-paypalpayment', [App\Http\Controllers\GiftCardController::class, 'processPaypalPayment'])->name('giftcard.paypalpayment');

Route::get('delivery-address', [App\Http\Controllers\DeliveryAddressController::class, 'index'])->name('delivery-address.index');
Route::post('store-firebase-service', [App\Http\Controllers\HomeController::class,'storeFirebaseService'])->name('store-firebase-service');
