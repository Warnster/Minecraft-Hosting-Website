<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-broadcast', function() {
    $update = 55;
    broadcast(new \App\Events\Test($update));
    $yes = '';
});

Auth::routes();

Route::namespace('Client')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/server', 'HomeController@createServer')->name('server');
    Route::post('/minecraft/server', 'ServerManagerController@createMinecraftServer');
});

/*Auth::routes();

*/
