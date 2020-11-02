<?php

use Illuminate\Support\Facades\Route;

Route::get('/proxy/source', 'ProxyController@source');
Route::get('/proxy/asset', 'ProxyController@asset');
