<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PhonePeController;
use App\Http\Controllers\RazorPayController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;

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

Route::get('/index', function () {
    return view('index');
});

Route::get('login', [loginController::class, 'showLogin'])->name('show.login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('login', [loginController::class, 'login'])->name('login');

// Product Routes
Route::get('/', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


// Cart Routes
Route::get('cart', [CartController::class, 'showCart'])->name('cart.show');
Route::get('cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

// checkout Routes
Route::get('checkout/{id?}', [OrderController::class, 'checkout'])->name('checkout');
Route::get('checkout/add/{id}', [OrderController::class, 'addSingleProductToCheckout'])->name('checkout.addSingle');






// Restrict checkout and payment to logged-in users only
Route::middleware(['auth'])->group(function () {

    Route::post('/add-address', [OrderController::class, 'addAddress'])->name('addAddress');
    Route::post('/update-selected-address', [OrderController::class, 'updateSelectedAddress']);

    //Payment with: Card
    Route::post('checkout', [OrderController::class, 'processCheckout'])->name('checkout.process');
    Route::get('checkout/success', [OrderController::class, 'success'])->name('checkout.success');
    Route::get('checkout/cancel', [OrderController::class, 'cancel'])->name('checkout.cancel');
    Route::post('/webhook', [OrderController::class, 'webhook'])->name('checkout.webhook');

    //Payment with: Phonepe
    Route::get('payment', [PhonePeController::class, 'index'])->name('payment');
    Route::post('pay-now', [PhonePeController::class, 'submitPaymentForm'])->name('pay-now');
    Route::any('confirm', [PhonePeController::class, 'confirmPayment'])->name('confirm');

    //Payment with: Paypal
    Route::get('process-transaction', [PayPalController::class, 'processTransaction'])->name('processTransaction');
    Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
    Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');

    //Google pay and paytm pending
    Route::get('/subscriptions', [SubscriptionController::class, 'showSubscriptions'])->name('subscriptions.index');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Profile and logout routes
    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('logout', [loginController::class, 'logout'])->name('logout');

});


