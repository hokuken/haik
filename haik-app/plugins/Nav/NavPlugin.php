<?php
namespace Hokuken\Haik\Plugin\Nav;

use Hokuken\Haik\Plugin\BasePlugin;

class NavPlugin extends BasePlugin {

    /**
     * @inheritdoc
     */
    public function convert($params = array(), $body = '')
    {
        // Nav Specific Syntax
        //
        // * Item: URL               # text link to URL
        // * Item                   # Dropdown trigger
        //     * Sub-Item: URL       # text Link to URL
        // * ![alt](URL): URL        # image link to URL
        //
        // /[Action](button URL)    # Button link to URL
        //
        // Some Text                # Some text in p.navbar-text
        //
        // * Item: URL              # Left alignment link list
        // * Item: URL              #
        // {.navbar-left}           # class-name
        //
        // Some Text {.navbar-left} # Left alignment nav text
        //

        $parser = new NavParser();
        return $parser->transform($body);
    }

}
