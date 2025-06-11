<?php

use App\Http\Controllers\ManageProductController;
use App\Http\Controllers\ManageCartController;
use App\Http\Controllers\ManageOrderController;
use App\Http\Controllers\ManagePaymentController;
use App\Http\Controllers\ManageRecycleActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManageRewardController;
use App\Http\Controllers\ManageUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Route for User
    Route::get('/user-list', [ManageUserController::class, 'showUserList'])->name('user.list');
    Route::get('/add-user', [ManageUserController::class, 'addUser'])->name('add.user');
    Route::post('/add-user', [ManageUserController::class, 'createUser'])->name('create.user');
    Route::get('/edit-user/{id}', [ManageUserController::class, 'editUser'])->name('edit.user');
    Route::post('/edit-user/{id}', [ManageUserController::class, 'updateUser'])->name('update.user');
    Route::delete('/delete-user/{id}', [ManageUserController::class, 'deleteUser'])->name('delete.user');

    //Route for Product
    Route::get('/product', [ManageProductController::class, 'index'])->name('product');
    Route::get('/product-list', [ManageProductController::class, 'showProductList'])->name('product.list');
    Route::get('/view-product/{id}', [ManageProductController::class, 'viewProduct'])->name('view.product');
    Route::get('/add-product', [ManageProductController::class, 'addProduct'])->name('add.product');
    Route::post('/add-product', [ManageProductController::class, 'createProduct'])->name('create.product');
    Route::get('/edit-products/{id}', [ManageProductController::class, 'editProduct'])->name('edit.product');
    Route::post('/edit-products/{id}', [ManageProductController::class, 'updateProduct'])->name('update.product');
    Route::delete('/delete-products/{id}', [ManageProductController::class, 'deleteProduct'])->name('delete.product');

    //Route for Cart
    Route::get('/cart-list', [ManageCartController::class, 'showCartList'])->name('cart.list');
    Route::post('/add-cart/{id}', [ManageCartController::class, 'addToCart'])->name('add.cart');
    Route::post('/cart/update', [ManageCartController::class, 'updateCartQuantity'])->name('cart.update');
    Route::delete('/cart/delete', [ManageCartController::class, 'destroy'])->name('cart.delete');
    Route::get('/checkout', [ManageCartController::class, 'showCheckout'])->name('checkout.list');
    Route::post('/place-order', [ManageCartController::class, 'placeOrder'])->name('place.order');
    
    //Route for Banking Form
    Route::get('/banking/{order_id}', [ManageCartController::class, 'showBankingForm'])->name('banking.form');
    Route::post('/banking/submit', [ManageCartController::class, 'submitBankingForm'])->name('banking.submit');

    //Route for Order
    Route::get('/order-list', [ManageOrderController::class, 'showOrderList'])->name('order.list');
    Route::get('/order-progress', [ManageOrderController::class, 'showOrderProgress'])->name('order.progress');
    Route::get('/view-order/{id}', [ManageOrderController::class, 'viewOrder'])->name('view.order');
    Route::get('/findPrice', [ManageOrderController::class, 'findPrice'])->name('findPrice');
    Route::get('/add-order', [ManageOrderController::class, 'addOrder'])->name('add.order');
    Route::post('/add-order', [ManageOrderController::class, 'createOrder'])->name('create.order');
    Route::get('/edit-order/{id}', [ManageOrderController::class, 'editOrder'])->name('edit.order');
    Route::put('/edit-order/{id}', [ManageOrderController::class, 'updateOrder'])->name('update.order');
    Route::delete('/delete-order/{id}', [ManageOrderController::class, 'deleteOrder'])->name('delete.order');
    Route::get('/new-order-notifications', [ManageOrderController::class, 'newOrderNotifications'])->name('order.notifications');
    Route::get('/order/history', [ManageOrderController::class, 'orderHistory'])->name('order.history');
    Route::get('/purchase-history', [ManageOrderController::class, 'purchaseHistory'])->name('order.history_page');
 





    //Route for Payment
    Route::get('/payment-list', [ManagePaymentController::class, 'showPaymentList'])->name('payment.list');
    Route::get('/view-payment/{id}', [ManagePaymentController::class, 'viewPayment'])->name('view.payment');

    //Route for Recycle Activity
    Route::get('/recycle-activity-list', [ManageRecycleActivityController::class, 'showRecycleActivityList'])->name('recycle.activity.list');
    Route::get('/view-recycle-activity/{id}', [ManageRecycleActivityController::class, 'viewRecycleActivity'])->name('view.recycle.activity');
    Route::get('/add-recycle-activity', [ManageRecycleActivityController::class, 'addRecycleActivity'])->name('add.recycle.activity');
    Route::post('/add-recycle-activity', [ManageRecycleActivityController::class, 'createRecycleActivity'])->name('create.recycle.activity');
    Route::get('/edit-recycle-activity/{id}', [ManageRecycleActivityController::class, 'editRecycleActivity'])->name('edit.recycle.activity');
    Route::post('/edit-recycle-activity/{id}', [ManageRecycleActivityController::class, 'updateRecycleActivity'])->name('update.recycle.activity');
    Route::delete('/delete-recycle-activity/{id}', [ManageRecycleActivityController::class, 'deleteRecycleActivity'])->name('delete.recycle.activity');

    //Route for Reward
    Route::get('/reward-list', [ManageRewardController::class, 'showRewardList'])->name('reward.list');
    Route::get('/view-reward/{id}', [ManageRewardController::class, 'viewReward'])->name('view.reward');
    Route::post('/redeem-reward/{id}', [ManageRewardController::class, 'redeemReward'])->name('redeem.reward');
    Route::get('/add-reward', [ManageRewardController::class, 'addReward'])->name('add.reward');
    Route::post('/add-reward', [ManageRewardController::class, 'createReward'])->name('create.reward');
    Route::get('/edit-reward/{id}', [ManageRewardController::class, 'editReward'])->name('edit.reward');
    Route::post('/edit-reward/{id}', [ManageRewardController::class, 'updateReward'])->name('update.reward');
    Route::delete('/delete-reward/{id}', [ManageRewardController::class, 'deleteReward'])->name('delete.reward');
    Route::get('/redeem-reward-code', [ManageRewardController::class, 'rewardRedemption'])->name('reward.redemption');
    Route::post('/redeem-reward-code', [ManageRewardController::class, 'redeemRewardCode'])->name('redeem.reward.code');
    Route::get('/redeem-code-success', [ManageRewardController::class, 'redeemCodeSuccess'])->name('redeem.code.success');
    Route::get('/my-reward-list', [ManageRewardController::class, 'showMyRewardList'])->name('my.reward.list');
    Route::get('/view-my-reward/{id}', [ManageRewardController::class, 'viewMyReward'])->name('view.my.reward');

});


require __DIR__.'/auth.php';
