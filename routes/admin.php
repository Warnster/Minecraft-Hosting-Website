<?php


Route::get('/', 'HomeController@index')->name('home-admin');
Route::get('/home', 'HomeController@index')->name('home-admin');
Route::get('/cloud-servers', 'CloudServerController@index')->name('cloud-servers');;
