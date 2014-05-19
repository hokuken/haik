<?php
/*
|--------------------------------------------------------------------------
| Filr plugin Routes
|--------------------------------------------------------------------------
*/
$controller = 'Hokuken\Haik\Plugin\Filr\FilrPluginController';

Route::group(array('prefix' => 'cmd/filr'), function($routes) use ($controller)
{
    Route::post('/', array(
            'uses' => $controller.'@index',
            'as' => 'plugin.filr'
        )
    );

    Route::get('upload', array(
            'uses' => $controller.'@showForm',
            'as' => 'plugin.filr.form'
        )
    );
    Route::post('upload', array(
            'uses' => $controller.'@upload',
            'as' => 'plugin.filr.upload'
        )
    );
});
