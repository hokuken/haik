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

Route::any('login', array('uses' => 'SessionController@login', 'as' => 'login'));

Route::get('/logout', array('uses' => 'SessionController@logout', 'as' => '/logout'));

/*
|--------------------------------------------------------------------------
| Plugin Routes
|--------------------------------------------------------------------------
|
| If /haik-app/plugins/{plugin} dir has routes.php,
| then include them.
|
*/
$files = File::glob(app_path() . '/plugins/*/routes.php');
foreach ($files as $file_path)
{
    include $file_path;
}

Route::get('/{page}', 'PageController@show')
->where('page', '.*');
