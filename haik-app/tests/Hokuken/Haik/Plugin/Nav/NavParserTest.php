<?php
use Hokuken\Haik\Plugin\Nav\NavParser;

class NavParserTest extends TestCase {

    public function setUp()
    {
        $parser = new NavParser();
        $this->parser = $parser;
    }

    public function testTransform()
    {
        $result = $this->parser->transform('');
        $this->assertInternalType('string', $result);
    }

    public function testNotParseOrderedList()
    {
        $markdown = '
1. Item: URL
2. Item: URL
3. Item: URL
';

        $result = $this->parser->transform($markdown);
        $not_expected = array(
            'tag' => 'ol'
        );
        $this->assertNotTag($not_expected, $result);
    }

    public function testParseList()
    {
        $markdown = '
* Item: URL
* Item: http://www.google.com
* Item: https://www.google.com
* Item: PageName/SubPage
* Item: ./file.png
';
        $result = $this->parser->transform($markdown);

        $expected = array(
            'tag' => 'ul',
            'attributes' => array(
                'class' => 'nav navbar-nav'
            ),
            'children' => array(
                'count' => 5,
                'only' => array(
                    'tag' => 'li',
                ),
            ),
            'descendant' => array(
                'tag' => 'a',
                'content' => 'Item',
                'attributes' => array(
                    'href' => 'URL'
                ),
            ),
        );

        $this->assertTag($expected, $result);
    }

    public function testParseLineAsParagraph()
    {
        $markdown = '
Text 1
Text 2
Text 3
';
        $result = $this->parser->transform($markdown);
        $expected = array(
            'children' => array(
                'count' => 3,
                'only' => array(
                    'tag' => 'p'
                )
            ),
        );

        $this->assertTag($expected, $result);
    }

    public function testNestedList()
    {
        $markdown = '
* Item: URL
* Item
    * SubItem: URL
    * SubItem: URL
';
        $result = $this->parser->transform($markdown);
        $expected = array(
            'tag' => 'ul',
            'attributes' => array(
                'class' => 'nav navbar-nav'
            ),
            'child' => array(
                'tag' => 'li',
                'attributes' => array(
                    'class' => 'dropdown'
                ),
                'child' => array(
                    'tag' => 'a',
                    'attributes' => array(
                        'class' => 'dropdown-toggle',
                        'data-toggle' => 'dropdown'
                    ),
                ),
            ),
        );
        $this->assertTag($expected, $result);

        $expected = array(
            'tag' => 'ul',
            'attributes' => array(
                'class' => 'nav navbar-nav'
            ),
            'child' => array(
                'tag' => 'li',
                'attributes' => array(
                    'class' => 'dropdown'
                ),
                'child' => array(
                    'tag' => 'ul',
                    'attributes' => array(
                        'class' => 'dropdown-menu'
                    ),
                ),
            ),
        );

        $this->assertTag($expected, $result);
    }

    public function testNestedListContainsDivider()
    {
        $markdown = '
* Item: URL
* Item
    * SubItem: URL
    * ---
    * SubItem: URL
';
        $result = $this->parser->transform($markdown);
        $expected = array(
            'tag' => 'li',
            'attributes' => array(
                'class' => 'divider'
            ),
            'content' => '',
            'parent' => array(
                'tag' => 'ul',
                'attributes' => array(
                    'class' => 'dropdown-menu'
                ),
            ),
        );
        $this->assertTag($expected, $result);
    }

}
