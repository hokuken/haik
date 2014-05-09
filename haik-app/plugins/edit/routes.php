<?php
/*
|--------------------------------------------------------------------------
| Edit plugin Routes
|--------------------------------------------------------------------------
*/

Route::get('/cmd/edit/{page?}', array(
        'uses' => 'Hokuken\Haik\Plugin\Edit\Controller@showForm',
        'as' => 'plugin.edit'
    )
)->where('page', '.+');

Route::post('/cmd/edit', array(
        'uses' => 'Hokuken\Haik\Plugin\Edit\Controller@save',
        'as' => 'plugin.edit.post'
    )
);
