<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/events' , 'EventsController@index');
Route::get('/events/{id}', 'EventsController@read');
Route::put('/event-sections/{id}', 'EventSectionsController@reserve');