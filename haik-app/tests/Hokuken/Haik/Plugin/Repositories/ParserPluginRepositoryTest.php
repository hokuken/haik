<?php
use Hokuken\Haik\Plugin\Repository\ParserPluginRepository;

class ParserPluginRepositoryTest extends TestCase {

    public function testExists()
    {
        $parser = Mockery::mock('Michelf\MarkdownInterface');
        $repo = new ParserPluginRepository($parser);
        $plugin_name = 'edit';
        $this->assertTrue($repo->exists('edit'));
    }

}
