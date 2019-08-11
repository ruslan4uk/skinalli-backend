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

// Auth route
Route::post('/auth/register', 'API\AuthController@register');
Route::post('/auth/login', 'API\AuthController@login');
Route::post('/auth/logout', 'API\AuthController@logout');
Route::get('/auth/user', 'API\AuthController@user')->middleware('auth:api');

Route::apiResource('/admin/category', 'API\Admin\CategoryController')->middleware('auth:api');
Route::apiResource('/admin/tags', 'API\Admin\TagsController')->middleware('auth:api');
Route::post('/admin/photos/upload', 'API\Admin\PhotosController@upload');
Route::apiResource('/admin/photos', 'API\Admin\PhotosController')->middleware('auth:api');