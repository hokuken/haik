<?php
namespace Hokuken\Haik\Markdown;

use Illuminate\Support\Facades\Facade;

class ParserFacade extends Facade {
    
    protected static function getFacadeAccessor()
    {
        return 'markdown.parser';
    }

}
