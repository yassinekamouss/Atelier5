<?php

use App\Events\StockUpdated;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});