<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('login', function()
{
    return 'login';
});

Route::get('logout', function()
{
    return 'logout';
});

Route::get('cmd/{plugin}', function($plugin)
{
    return $plugin;
});

Route::any('/{page}', function($page)
{
    return $page;
})
->where('page', '.*');
