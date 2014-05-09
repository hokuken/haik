<?php
namespace Hokuken\Haik\Markdown;

use Illuminate\Foundation\Application;
use Hokuken\HaikMarkdown\HaikMarkdown as BaseHaikMarkdown;

class HaikMarkdown extends BaseHaikMarkdown {

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        parent::__construct();
    }

}
