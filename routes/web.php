<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('hello', function () {
    return "Hello authenticated user";
});
Route::get('token', function () {
    return csrf_token();
});
// Route::get('/login')->name('login');
Route::post('/register', [AuthController::class, 'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])->name('logout');
Route::prefix('blog')->group(function(){

    Route::post('/create',[BlogController::class,'create']);
    Route::patch('/update/{id}', [BlogController::class, 'update']);
    Route::delete('/delete/{id}',[BlogController::class,'destroy']);
});
// Route::get('/register')->name('register');
