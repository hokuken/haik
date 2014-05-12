<?php
namespace Hokuken\Haik\Markdown;

use Illuminate\Support\ServiceProvider;
use App;
use Hokuken\Haik\Markdown\HaikMarkdown;
use Hokuken\HaikMarkdown\Plugin\Basic\PluginRepository as BasicPluginRepository;
use Hokuken\HaikMarkdown\Plugin\Bootstrap\PluginRepository as BootstrapPluginRepository;
use Hokuken\Haik\Plugin\Repository\ParserPluginRepository as PackagedPluginRepository;

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

            $repository = new BasicPluginRepository($parser);
            $parser->registerPluginRepository($repository);

            $repository = new BootstrapPluginRepository($parser);
            $parser->registerPluginRepository($repository);

            $repository = new PackagedPluginRepository($parser);
            $parser->registerPluginRepository($repository);

            return $parser;
        });
    }

}
