<?php
namespace Hokuken\Haik\Page;

use ReflectionClass;
use Illuminate\Support\ServiceProvider;
use Page;

class PageServiceProvider extends ServiceProvider {
    
    public function boot()
    {
    }

    /**
     * register ServiceProvider
     */
    public function register()
    {
        $this->app->bind('PageMeta', function($app, $args)
        {
            $reflect  = new ReflectionClass('Hokuken\Haik\Page\PageMeta');
            $instance = $reflect->newInstanceArgs($args);
            return $instance;
        });
    }

}
