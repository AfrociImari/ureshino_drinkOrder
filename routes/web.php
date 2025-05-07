<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\DrinkMenuController;
use App\Http\Controllers\DrinkOrderController;
use App\Http\Controllers\DrinkCategoryController;
use App\Http\Controllers\CourseMenuController;
use App\Http\Controllers\OrderCartController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/userList', function () {
    return Inertia::render('UserList');
})->middleware(['auth', 'verified'])->name('userList');

Route::resource('/users', UserController::class);
Route::put('/users/{id}', [UserController::class, 'update']);

Route::get('/roomList', function () {
    return Inertia::render('Room');
})->middleware(['auth', 'verified'])->name('roomList');
Route::resource('/room', RoomController::class);

Route::get('/checkinList', function () {
    return Inertia::render('CheckIn');
})->middleware(['auth', 'verified'])->name('checkinList');
Route::resource('/checkin', CheckInController::class);

Route::get('/drinks', function () {
    return Inertia::render('DrinkMenu');
})->middleware(['auth', 'verified'])->name('drinks');
Route::resource('/drinkMenu', DrinkMenuController::class);

Route::get('/courses', function () {
    return Inertia::render('CourseMenu');
})->middleware(['auth', 'verified'])->name('courses');
Route::resource('/courseMenu', CourseMenuController::class);

Route::get('/orders', function () {
    return Inertia::render('DrinkOrder');
})->middleware(['auth', 'verified'])->name('orders');
Route::resource('/drinkOrder', DrinkOrderController::class);

Route::get('/categories', function () {
    return Inertia::render('DrinkCategory');
})->middleware(['auth', 'verified'])->name('categories');
Route::resource('/drinkCategory', DrinkCategoryController::class);

//OrderCart Controller
Route::resource('/orderCart', OrderCartController::class);

// Route::get('/showReceiptMobile', function () {
//     return Inertia::render('MobileApp/MobileReceipt');
// })->name('showReceiptMobile');

// Route::get('/orderCart', function () {
//     return Inertia::render('MobileApp/OrderCart');
// })->name('orderCart');

// Generate encrypted Url
Route::post('/drinkMenu/getEncryptedUrl', [DrinkMenuController::class, 'generateEncryptedMobileUrl']);

// Define the route for rendering mobile screen
Route::get('/drinkMenu/mobile/{encrypted_checkin_id}', [DrinkMenuController::class, 'gotoMobileMenu']);

// Define the route for retrieving variants by product name
Route::get('/drinkMenu/variants/{product_name}', [DrinkMenuController::class, 'getVariantsByProductName']);

// Generate qrCode for empty data
Route::put('/checkin/updateQrCode/{checkin_id}', [CheckInController::class, 'updateQrCode']);

// update order_stop data
Route::put('/checkin/orderStop/{checkin_id}', [CheckInController::class, 'orderStop']);

Route::get('/qrCodeError', function () {
    return Inertia::render('MobileApp/QrCodeError');
})->name('qrCodeError');


require __DIR__.'/auth.php';
