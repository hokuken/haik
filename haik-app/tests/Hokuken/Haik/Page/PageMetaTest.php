<?php

use Hokuken\Haik\Page\PageMeta;
use Symfony\Component\Yaml\Yaml;

class PageMetaTest extends TestCase {

    public function setUp()
    {
        $page = Page::find(1);//FrontPage
        $this->page = $page;

        $this->testData = array(
            'title' => 'TestTitle',
            'template_name' => 'top',
            'description' => "multi\nline\ndescription",
            'group' => array(
                'key' => 'value'
            )
        );
        $this->saveTestData();

        $page_meta = new PageMeta($this->page, false);
        $this->pageMeta = $page_meta;
    }

    public function saveTestData()
    {
        PageMetaFragment::where('haik_page_id', $this->page->id)->delete();

        $data = array_dot($this->testData);
        foreach ($data as $key => $value)
        {
            PageMetaFragment::create(array(
                'haik_page_id' => $this->page->id,
                'key' => $key,
                'value' => $value,
            ));
        }
    }

    public function setUpData()
    {
        $this->pageMeta->setAll($this->pageMeta->read(), true);
    }

    public function testGetPage()
    {
        $this->assertEquals($this->page, $this->pageMeta->getPage());
    }
    public function testRead()
    {
        $data = $this->pageMeta->read();
        $this->assertEquals($this->testData, $data);
    }

    public function testGet()
    {
        $this->setUpData();

        $value = $this->pageMeta->get('title');
        $this->assertEquals('TestTitle', $value);

        $value = $this->pageMeta->get('unknown');
        $this->assertNull($value);

        $value = $this->pageMeta->get('unknown', 'foobar');
        $this->assertEquals('foobar', $value);

        $value = $this->pageMeta->get('group.key');
        $this->assertEquals('value', $value);

        $value = $this->pageMeta->get('group.unknown');
        $this->assertNull($value);
        
        $value = $this->pageMeta->get('unknowngroup.unknown');
        $this->assertNull($value);
    }

    public function testGetAll()
    {
        $this->setUpData();

        $data = $this->pageMeta->getAll();
        $this->assertEquals($this->testData, $data);
    }

    public function testToYaml()
    {
        $this->setUpData();
        $yaml = $this->pageMeta->toYaml();
        $expected = Yaml::dump($this->testData);
        $this->assertEquals($expected, $yaml);
    }

    public function testHas()
    {
        $this->setUpData();
        $this->assertTrue($this->pageMeta->has('title'));
        $this->assertFalse($this->pageMeta->has('unknown'));
    }

    public function testSet()
    {
        $expected = 'test title';
        $this->pageMeta->set('title', $expected);
        $value = $this->pageMeta->get('title');
        $this->assertEquals($expected, $value);

        $expected = 'grouped value';
        $this->pageMeta->set('group.value', $expected);
        $value = $this->pageMeta->get('group.value');
        $this->assertEquals($expected, $value);

        $expected = 'new value';
        $this->pageMeta->set('newkey', $expected);
        $value = $this->pageMeta->get('newkey');
        $this->assertEquals($expected, $value);

        $expected = 'more.nested.value';
        $this->pageMeta->set('more.nested.value', $expected);
        $value = $this->pageMeta->get('more.nested.value');
        $this->assertEquals($expected, $value);

    }

    public function testSetAll()
    {
        $this->pageMeta->setAll($this->testData);
        $data = $this->pageMeta->getAll();
        $this->assertEquals($this->testData, $data);
//        $this->assertAttributeEquals($this->testData, 'data', $this->pageMeta);
    }

    public function testIsDirtyWhenSetNewKey()
    {
        $this->setUpData();
        $this->pageMeta->set('newkey', 'new value');
        $this->assertTrue($this->pageMeta->isDirty());
    }

    public function testIsDirtyWhenSetExistedKey()
    {
        $this->setUpData();
        $this->pageMeta->set('title', 'new title');
        $this->assertTrue($this->pageMeta->isDirty());
    }

    public function testIsDirtyWhenSetSameValueToExistedKey()
    {
        $this->setUpData();
        $this->pageMeta->set('title', 'TestTitle');
        $this->assertFalse($this->pageMeta->isDirty());
    }

    public function testIsDirtyWhenRemoveExistedKey()
    {
        $this->setUpData();
        $this->pageMeta->remove('title');
        $this->assertTrue($this->pageMeta->isDirty());
    }

    public function testIsDirtyWhenReadData()
    {
        $this->setUpData();
        $this->pageMeta->set('title', 'new title');
        $this->assertTrue($this->pageMeta->isDirty());

        $this->pageMeta->read();
        $this->assertFalse($this->pageMeta->isDirty());
    }
    
    public function testSave()
    {
        $this->setUpData();
        $this->pageMeta->set('newkey', 'new value');
        $this->pageMeta->set('title', 'UpdatedTitle');
        $this->pageMeta->save();

        $expected = $this->testData;
        $expected['newkey'] = 'new value';
        $expected['title'] = 'UpdatedTitle';

        $data = $this->pageMeta->read();
        
        $this->assertEquals($expected, $data);
    }

    public function testDelete()
    {
        $this->pageMeta->delete();
        $data = $this->pageMeta->getAll();
        $this->assertEquals(array(), $data);

        $data = $this->pageMeta->read();
        $this->assertEquals(array(), $data);
    }

}
