<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingControllerV2;
use App\Http\Controllers\AutoControllerV2;
use App\Http\Controllers\EmploeeBookingControllerV2;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('filter', [BookingControllerV2::class, 'filterBooking'])->name('filter');
Route::get('filter_auto', [AutoControllerV2::class, 'filterAuto'])->name('filter_auto');
Route::get('filter_booking', [EmploeeBookingControllerV2::class, 'filterEmploeeBooking'])->name('filter_booking');