<?php
namespace Hokuken\Haik\Markdown;

use Illuminate\Support\ServiceProvider;
use App;
use Hokuken\Haik\Markdown\HaikMarkdown;
use Hokuken\HaikMarkdown\Plugin\Basic\PluginRepository;

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
            $parser = new HaikMarkdown($app);
            $repository = new PluginRepository($parser);
            $parser->registerPluginRepository($repository);
            return $parser;
        });
    }

}
