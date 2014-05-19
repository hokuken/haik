<?php
/*
|--------------------------------------------------------------------------
| List plugin Routes
|--------------------------------------------------------------------------
*/
$controller = 'Hokuken\Haik\Plugin\PageList\PageListPluginController';

Route::group(array('prefix' => 'cmd'), function($routes) use ($controller)
{
    Route::get('list', array(
            'uses' => $controller.'@show',
            'as' => 'plugin.list'
        )
    );

    Route::get('list.{format}', array(
            'uses' => $controller. '@show',
            'as' => 'plugin.list'
        )
    )->where('format', 'json');
});

/*
|--------------------------------------------------------------------------
| List plugin: sitemap.xml
|--------------------------------------------------------------------------
*/

Route::get('sitemap.xml', array(
        'uses' => $controller.'@siteMap',
        'as' => 'plugin.list.sitemap'
    )
);
