<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
   return redirect('/');
});

Route::get('/content', function () {
   return view ('/content');
});

Route::get('/trainers', function () {
   return view ('/trainers');
});

Route::get('/why', function () {
   return view ('/why');
});

Route::get('/', [UserController::class, 'loadlogin']);
Route::post('/login', [UserController::class, 'userLogin'])->name('userLogin');

Route::get('/register', [UserController::class, 'loadRegister']);
Route::post('/register', [UserController::class, 'userRegister'])->name('userRegister');

Route::get('/logout', [UserController::class, 'logout']);
Route::get('/home', [UserController::class, 'home']);

Route::get('/forget',[UserController::class,'forgotPassword'])->name('forget');

Route::post('/forget_password',[UserController::class,'processForgetPassword'])->name('forget_password');
Route::get('/reset-password{token}',[UserController::class,'resetPassword'])->name('resetPassword');
Route::post('/process-reset-password',[UserController::class,'processResetPassword'])->name('processResetPassword');
