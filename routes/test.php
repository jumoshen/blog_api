<?php

Route::post('register', 'TestController@register');
Route::post('login', 'TestController@login');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('policy-test', 'TestController@testPolicy');
});