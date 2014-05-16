<?php
/*
|--------------------------------------------------------------------------
| Edit plugin Routes
|--------------------------------------------------------------------------
*/
$controller = 'Hokuken\Haik\Plugin\Edit\EditPluginController';

Route::group(array('prefix' => 'cmd/edit'), function($routes) use ($controller)
{
    Route::get('{page?}', array(
            'uses' => $controller.'@showForm',
            'as' => 'plugin.edit'
        )
    )->where('page', '.+');

    Route::post('/', array(
            'uses' => $controller.'@save',
            'as' => 'plugin.edit.post'
        )
    );
});

/*
|--------------------------------------------------------------------------
| Edit plugin: delete command Routes
|--------------------------------------------------------------------------
*/
Route::group(array('prefix' => 'cmd/delete'), function($routes) use ($controller)
{
    Route::get('{page?}', array(
            'uses' => $controller.'@delete',
            'as' => 'plugin.edit.delete'
        )
    )->where('page', '.+');

    Route::post('/', array(
            'uses' => $controller.'@delete',
            'as' => 'plugin.edit.forceDelete'
        )
    );
});
