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
Route::get('clients/search', 'api\ClientsController@search');
Route::get('products/search', 'api\ProductsController@search');
Route::get('orders/search', 'api\OrdersController@search');
Route::get('invoices/search', 'api\InvoicesController@search');

// Delete As Array
Route::delete('clients/delete', 'api\ClientsController@batchDelete');
Route::delete('products/delete', 'api\ProductsController@batchDelete');
Route::delete('orders/delete', 'api\OrdersController@batchDelete');
Route::delete('invoices/delete', 'api\InvoicesController@batchDelete');

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



