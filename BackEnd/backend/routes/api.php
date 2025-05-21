<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usercontroller;

Route::post('/register',[Usercontroller::class, 'register']);
Route::post('/login',[Usercontroller::class, 'login']);
Route::post('/logout',[Usercontroller::class, 'logout']);
Route::get('/dashboard', [Usercontroller::class, 'dashboard']);
