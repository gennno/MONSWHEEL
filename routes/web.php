<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

Route::get('videotron', function () {
    return view('videotron');
});

Route::get('dashboard', function () {
    return view('dashboard');
});
    