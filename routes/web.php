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
Route::get('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'viewLogin'])->name('view.login')->middleware('check.for.login');
Route::post('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'checkLogin'])->name('check.login');


Route::group(['prefix'=>'admin', 'middleware'=>'auth:admin'], function (){
    Route::get('/index', [App\Http\Controllers\Admins\AdminsController::class, 'index'])->name('admins.dashboard');


    //admins
    Route::get('/all-admins', [App\Http\Controllers\Admins\AdminsController::class, 'allAdmins'])->name('admins.all');
    Route::get('/create-admins', [App\Http\Controllers\Admins\AdminsController::class, 'createAdmins'])->name('admins.create');
    Route::post('/create-admins', [App\Http\Controllers\Admins\AdminsController::class, 'storeAdmins'])->name('admins.store');
    Route::get('/delete-admins/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteAdmins'])->name('admins.delete');



    //hotels
    Route::get('/all-hotels', [App\Http\Controllers\Admins\AdminsController::class, 'allHotels'])->name('hotels.all');
    Route::get('/create-hotels', [App\Http\Controllers\Admins\AdminsController::class, 'createHotels'])->name('hotels.create');
    Route::post('/create-hotels', [App\Http\Controllers\Admins\AdminsController::class, 'storeHotels'])->name('hotels.store');
    Route::get('/edit-hotels/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'editHotels'])->name('hotels.edit');
    Route::post('/update-hotels/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'updateHotels'])->name('hotels.update');
    Route::get('/delete-hotels/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteHotels'])->name('hotels.delete');

    //rooms
    Route::get('/all-rooms', [App\Http\Controllers\Admins\AdminsController::class, 'allRooms'])->name('rooms.all');
    Route::get('/create-rooms', [App\Http\Controllers\Admins\AdminsController::class, 'createRooms'])->name('rooms.create');
    Route::post('/create-rooms', [App\Http\Controllers\Admins\AdminsController::class, 'storeRooms'])->name('rooms.store');
    Route::get('/delete-rooms/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteRooms'])->name('rooms.delete');

    //bookings
    Route::get('/all-bookings', [App\Http\Controllers\Admins\AdminsController::class, 'allBookings'])->name('bookings.all');
    Route::get('/edit-status/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'editStatus'])->name('bookings.edit.status');
    Route::post('/update-status/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'updateStatus'])->name('bookings.update.status');
    Route::get('/delete-booking/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteBooking'])->name('bookings.delete');

    //users
    Route::get('/all-users', [App\Http\Controllers\Admins\AdminsController::class, 'allUsers'])->name('users.all');
    Route::get('/delete-users/{id}', [App\Http\Controllers\Admins\AdminsController::class, 'deleteUsers'])->name('users.delete');

    //getdata
    Route::get('/getdata', [App\Http\Controllers\Admins\AdminsController::class, 'getData'])->name('get.data');
    //getRoomsData
    Route::get('/getRoomsData', [App\Http\Controllers\Admins\AdminsController::class, 'getRoomsData'])->name('get.rooms.data');
    //getBookingsData
    Route::get('/getBookingsData', [App\Http\Controllers\Admins\AdminsController::class, 'getBookingsData'])->name('get.bookings.data');

    //performance-report
    Route::get('/performance-report', [App\Http\Controllers\Admins\AdminsController::class, 'performanceReport'])->name('performance.report');

    //downloadPerformanceReport
   //Route::get('/download-performance-report', [App\Http\Controllers\Admins\AdminsController::class, 'downloadPerformanceReport'])->name('download.performance.report');

});
