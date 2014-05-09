<?php
namespace Hokuken\Haik\Markdown;

use Illuminate\Support\ServiceProvider;
use App;
use Hokuken\Haik\Markdown\HaikMarkdown;

class ParsingServiceProvider extends ServiceProvider {
    
    public function boot()
    {
    }

    /**
     * register ServiceProvider
     */
    public function register()
    {
        $this->app['markdown.parser'] = $this->app->share(function($app)
        {
            return new HaikMarkdown($app);
        });
    }

}
