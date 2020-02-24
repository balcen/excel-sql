<?php

use Illuminate\Http\Request;

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

// Auth
Route::post('auth/register', 'api\AuthController@register');
Route::post('auth/login', 'api\AuthController@login');
Route::get('auth/user', 'api\AuthController@me');
Route::post('auth/logout', 'api\AuthController@logout');
Route::get('auth/refresh', 'api\AuthController@refresh');

// Search
Route::get('clients/search', 'api\ClientsController@searchAll');
Route::get('products/search', 'api\ProductsController@searchAll');
Route::get('orders/search', 'api\OrdersController@searchAll');
Route::get('invoices/search', 'api\InvoicesController@searchAll');

// Delete As Array
Route::delete('clients/deleteAll', 'api\ClientsController@deleteAll');
Route::delete('products/deleteAll', 'api\ProductsController@deleteAll');
Route::delete('orders/deleteAll', 'api\OrdersController@deleteAll');
Route::delete('invoices/deleteAll', 'api\InvoicesController@deleteAll');

Route::post('upload', 'api\UploadController@import');

// Upload File
Route::post('clients/upload', 'api\ClientsController@upload');
Route::post('products/upload', 'api\ProductsController@upload');
Route::post('orders/upload', 'api\OrdersController@upload');
Route::post('invoices/upload', 'api\InvoicesController@upload');

Route::resources([
    'clients' => 'api\ClientsController',
    'products' => 'api\ProductsController',
    'orders' => 'api\OrdersController',
    'invoices' => 'api\InvoicesController'
]);



