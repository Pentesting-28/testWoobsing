<?php

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

##########################################################################################################
########## THE REQUIRE_ONCE STATEMENT IS IDENTICAL TO REQUIRE EXCEPT THAT PHP WILL CHECK        ##########
########## TO SEE IF THE FILE HAS ALREADY BEEN INCLUDED,AND IF SO, IT DOESN'T REQUIRE IT AGAIN. ##########
##########################################################################################################

require_once __DIR__ . '/fortify.php';//Fortify provider routes

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sessions', function () {
    return view('sessions');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    // 'verified' //Fortify Provider Middleware
    'auth.verify_user_email', // Middleware by my creation
    'auth.set_user_cookie', // Middleware by set user cookie 'origin_sesion'
    'auth.2fa_session_limit' // Middleware 30 minutes to expire the user's session if they have 2FA authentication
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
