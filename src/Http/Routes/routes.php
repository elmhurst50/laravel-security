<?php

Route::group(['middleware' => ['web']], function(){
    Route::get('apply-security-cookie/{key}', ['as' => 'security.cookie.apply', 'uses' => '\SamJoyce777\LaravelSecurity\Http\Controllers\SecurityController@applyCookie']);
});
