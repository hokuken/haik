<?php
namespace Hokuken\Haik\Site;

use Illuminate\Support\ServiceProvider;
use App;
use Hokuken\Haik\Site\Config as HaikConfig;

class SiteServiceProvider extends ServiceProvider {
    
    public function boot()
    {
    }

    /**
     * register ServiceProvider
     */
    public function register()
    {
        $this->app['site.config'] = $this->app->share(function($app)
        {
            $config = new HaikConfig();

            return $config;
        });
    }

}
