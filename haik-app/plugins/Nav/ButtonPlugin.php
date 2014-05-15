<?php
namespace Hokuken\Haik\Plugin\Nav\Parser;

use Hokuken\HaikMarkdown\Plugin\Bootstrap\Button\ButtonPlugin as BootstrapButtonPlugin;

class ButtonPlugin extends BootstrapButtonPlugin {

    const SUFFIX_CSS_CLASS_NAME = 'navbar-btn';

    protected function createClassAttribute()
    {
        return parent::createClassAttribute() . ' ' . self::SUFFIX_CSS_CLASS_NAME;
    }

}
