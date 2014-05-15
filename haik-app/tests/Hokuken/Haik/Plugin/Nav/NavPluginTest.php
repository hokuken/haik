<?php

use Hokuken\Haik\Markdown\HaikMarkdown;
use Hokuken\Haik\Plugin\Repository\ParserPluginRepository;
use Hokuken\Haik\Plugin\Nav\NavPlugin;


class NavPluginTest extends TestCase {

    public function setUp()
    {
        $parser = new HaikMarkdown();
        $repository = new ParserPluginRepository($parser);
        $parser->registerPluginRepository($repository);
        $plugin = new NavPlugin($parser);
        $this->plugin = $plugin;
    }

    public function testConvert()
    {
        $result = $this->plugin->convert();
        $this->assertInternalType('string', $result);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testThrowsExceptionWhenInlineCalled()
    {
        $this->plugin->inline();
    }

    public function testAlignRightOption()
    {
        $expected = array(
            'tag' => 'ul',
            'attributes' => array(
                'class' => 'nav navbar-nav navbar-right',
            )
        );

        $params = array('right');
        $body = '
* Item
* Item
* Item
';
        $result = $this->plugin->convert($params, $body);
        $this->assertTag($expected, $result);

        $params = array('align' => 'right');
        $result = $this->plugin->convert($params, $body);
        $this->assertTag($expected, $result);
    }

    public function testAlignLeftOption()
    {
        $expected = array(
            'tag' => 'ul',
            'attributes' => array(
                'class' => 'nav navbar-nav navbar-left',
            )
        );

        $params = array('left');
        $body = '
* Item
* Item
* Item
';
        $result = $this->plugin->convert($params, $body);
        $this->assertTag($expected, $result);

        $params = array('align' => 'left');
        $result = $this->plugin->convert($params, $body);
        $this->assertTag($expected, $result);
    }

    public function testHasFormOption()
    {
        $expected = array(
            'tag' => 'form',
            'attributes' => array(
                'class' => 'navbar-form',
            )
        );

        $params = array('form');
        $body = '
<form role="search">
  <div class="form-group">
    <input type="text" class="form-control" placeholder="Search">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>
';
        $result = $this->plugin->convert($params, $body);
        $this->assertTag($expected, $result);

        $params = array('form' => true);
        $result = $this->plugin->convert($params, $body);
        $this->assertTag($expected, $result);

        $params = array('hasForm' => true);
        $result = $this->plugin->convert($params, $body);
        $this->assertTag($expected, $result);
    }


}
