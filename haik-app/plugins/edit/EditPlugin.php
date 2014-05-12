<?php
namespace Hokuken\Haik\Plugin\Edit;

use App;
use Config;
use Hokuken\Haik\Plugin\BasePlugin;
use RuntimeException;


class EditPlugin extends BasePlugin {

    public function inline($params = array(), $body = '')
    {
        if (count($params) > 0)
        {
            $pagename = array_pop($params);
        }
        else if (App::isAlias('page.current'))
        {
            $pagename = App::make('page.current');
        }
        else
        {
            $pagename = Config::get('haik.page.default');
        }
        return link_to_route('plugin.edit', $body, $pagename);
    }

}
