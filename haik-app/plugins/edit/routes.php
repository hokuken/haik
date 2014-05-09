<?php
/*
|--------------------------------------------------------------------------
| Edit plugin Routes
|--------------------------------------------------------------------------
*/

Route::get('/cmd/edit/{page?}', 'Hokuken\Haik\Plugin\Edit\Controller@showForm')->where('page', '.+');

Route::post('/cmd/edit', 'Hokuken\Haik\Plugin\Edit\Controller@save');
