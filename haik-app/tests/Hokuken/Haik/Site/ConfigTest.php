<?php
use Hokuken\Haik\Site\Config as HaikConfig;

class ConfigTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        $test_data = array(
            'site.title' => 'Test Site',
            'site.description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'head.tags' => '<meta name="author" content="Test Author">',
            'modified_at' => '2014-05-05 00:00:00'
        );
        $this->testData = array();
        foreach ($test_data as $key => $value)
        {
            array_set($this->testData, $key, $value);
        }

        $config = new HaikConfig();
        $this->config = $config;
        $this->config->setAll($test_data);
    }

    public function tearDown()
    {
        $this->config->deleteAll();
    }

    public function testService()
    {
        $expected = 'value';
        $config = App::make('site.config');
        $config->set('new.key', $expected);
        $result = App::make('site.config')['new.key'];
        $this->assertEquals($expected, $result);
    }

    public function testFacade()
    {
        $expected = 'foo, bar, buzz';
        Site::set('test.text', $expected);
        $result = Site::get('test.text');
        $this->assertEquals($expected, $result);
    }

    public function testGet()
    {
        $result = $this->config->get('site.title');
        $expected = array_get($this->testData, 'site.title');
        $this->assertEquals($expected, $result);
    }

    public function testGetAll()
    {
        $data = $this->config->getAll();
        $this->assertEquals($this->testData, $data);
    }

    public function testSet()
    {
        $expected = 'prefix=2';
        $this->config->set('head.attributes', $expected);
        $result = $this->config->get('head.attributes');
        $this->assertEquals($expected, $result);
    }

    public function testRemove()
    {
        $expected = microtime();
        $this->config->remove('site.description');
        $result = $this->config->has('site.description');
        $this->assertFalse($result);
    }

    public function testRemoveAll()
    {
        $this->config->removeAll();
        $result = $this->config->getAll();
        $this->assertEquals(0, count($result));
    }

    public function testSave()
    {
        $this->config->save();
        $array = ConfigFragment::lists('value', 'key');
        $data = array_dot($this->config->getAll());
        $this->assertEquals($array, $data);
    }

    public function testRead()
    {
        $this->config->read();
        $array = ConfigFragment::lists('value', 'key');
        $data = array_dot($this->config->getAll());
        $this->assertEquals($array, $data);
    }

    public function testDelete()
    {
        $key = 'head.tags';
        $this->config->remove($key);
        $this->config->delete();
        $this->config->read();
        $this->assertFalse($this->config->has($key));

        $result = ConfigFragment::where('key', $key)->first();
        $this->assertNull($result);
    }

    public function testDeleteAll()
    {
        $this->config->deleteAll();
        $this->config->read();
        $data = $this->config->getAll();
        $this->assertEquals(0, count($data));

        $collection = ConfigFragment::all()->toArray();
        $this->assertEquals(0, count($collection));
    }

}
