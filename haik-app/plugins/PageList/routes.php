<?php
/*
|--------------------------------------------------------------------------
| List plugin Routes
|--------------------------------------------------------------------------
*/
$controller = 'Hokuken\Haik\Plugin\PageList\PageListPluginController';

Route::group(array('prefix' => 'cmd/list'), function($routes) use ($controller)
{
    Route::get('/', array(
            'uses' => $controller.'@show',
            'as' => 'plugin.list'
        )
    );
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
