<?php
/*
|--------------------------------------------------------------------------
| Edit plugin Routes
|--------------------------------------------------------------------------
*/

Route::group(array('prefix' => 'cmd/edit'), function($routes)
{
    $controller = 'Hokuken\Haik\Plugin\Edit\EditPluginController';
    Route::get('{page?}', array(
            'uses' => $controller.'@showForm',
            'as' => 'plugin.edit'
        )
    )->where('page', '.+');

    Route::post('/cmd/edit', array(
            'uses' => $controller.'@save',
            'as' => 'plugin.edit.post'
        )
    );
});
