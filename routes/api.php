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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('auth/register', 'api\AuthController@register');
Route::post('auth/login', 'api\AuthController@login');
Route::middleware('jwt.auth')->group(function() {
    Route::get('auth/user', 'api\AuthController@me');
    Route::post('auth/logout', 'api\AuthController@logout');
});
Route::middleware('jwt.refresh')->group(function() {
    Route::get('auth/refresh', 'api\AuthController@refresh');
});

// Search
Route::get('clients/search', 'api\ClientsController@searchAll');
Route::get('products/search', 'api\ProductsController@searchAll');
Route::get('orders/search', 'api\OrdersController@searchAll');
Route::get('invoices/search', 'api\InvoicesController@searchAll');

Route::delete('clientsDeleteAll', 'api\ClientsController@deleteAll');
Route::delete('productsDeleteAll', 'api\ProductsController@deleteAll');
Route::delete('ordersDeleteAll', 'api\OrdersController@deleteAll');
Route::delete('invoicesDeleteAll', 'api\InvoicesController@deleteAll');

Route::resources([
    'clients' => 'api\ClientsController',
    'products' => 'api\ProductsController',
    'orders' => 'api\OrdersController',
    'invoices' => 'api\InvoicesController'
]);

Route::post('upload', 'api\UploadController@import');

Route::post('clients/upload', 'api\ClientsController@upload');
Route::post('products/upload', 'api\ProductsController@upload');
Route::post('orders/upload', 'api\OrdersController@upload');
Route::post('invoices/upload', 'api\InvoicesController@upload');


