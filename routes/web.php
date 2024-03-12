<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/about', [App\Http\Controllers\HomeController::class, 'about'])->name('about');
Route::get('/services', [App\Http\Controllers\HomeController::class, 'services'])->name('services');
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

//hotels
Route::get('hotels/rooms/{id}', [App\Http\Controllers\Hotels\HotelsController::class, 'rooms'])->name('hotel.rooms');

Route::get('hotels/rooms-details/{id}', [App\Http\Controllers\Hotels\HotelsController::class, 'roomDetails'])->name('hotel.rooms.details');


Route::post('hotels/rooms-booking/{id}', [App\Http\Controllers\Hotels\HotelsController::class, 'roomBooking'])->name('hotel.rooms.booking');
//pay
Route::get('hotels/pay', [App\Http\Controllers\Hotels\HotelsController::class, 'payWithPaypal'])->name('hotel.pay')->middleware('check.for.price');
Route::get('hotels/success', [App\Http\Controllers\Hotels\HotelsController::class, 'success'])->name('hotel.success')->middleware('check.for.price');

//Users
Route::get('users/my-booking', [App\Http\Controllers\Users\UsersController::class, 'myBookings'])->name('users.bookings')->middleware('auth:web');

// admin panel
Route::get('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'viewLogin'])->name('view.login');
Route::post('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'checkLogin'])->name('check.login');


Route::get('admin/index', [App\Http\Controllers\Admins\AdminsController::class, 'index'])->name('admins.dashboard');
