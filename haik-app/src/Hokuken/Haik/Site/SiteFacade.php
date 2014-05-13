<?php
namespace Hokuken\Haik\Site;

use Illuminate\Support\Facades\Facade;

class SiteFacade extends Facade {
    
    protected static function getFacadeAccessor()
    {
        return 'site.config';
    }

}
