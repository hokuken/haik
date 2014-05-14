<?php
namespace Hokuken\Haik\Page;

use App;
use Page;

class LayoutPage extends Page {

    public function parseBody()
    {
        App::bind('page.layout', $this);
        parent::parseBody();
        App::offsetUnset('page.layout');
        return $this;
    }

}
