<?php
namespace Hokuken\Haik\Plugin\Edit;

use Config;
use BaseController;

class Controller extends BaseController {

    public function showForm($page = '')
    {
        if ($page === '')
        {
            $page = Config::get('haik.page.default');
        }
        return 'edit: ' . $page;
    }

    public function save()
    {
        $page = Input::get('name');
        return 'save: ' . $page;
    }

}
