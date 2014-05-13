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
        $this->pageMeta->setAll($this->pageMeta->read()->getAll(), true);
    }

    public function testGetPage()
    {
        $this->assertEquals($this->page, $this->pageMeta->getPage());
    }

    public function testRead()
    {
        $data = $this->pageMeta->read()->getAll();
        $this->assertEquals($this->testData, $data);
    }

    public function testSave()
    {
        $key = 'foo.bar';
        $value = 'buzz';
        $this->pageMeta->set($key, $value);
        $this->pageMeta->save();
        $page = $this->pageMeta->getPage();

        $fragment = PageMetaFragment::where('haik_page_id', $page->id)->where('key', $key)->first();
        $this->assertEquals($value, $fragment->value);
    }

    public function testSaveWithoutPage()
    {
        $page = new Page();
        $page_meta = new PageMeta($page);
        $key = 'hoge';
        $value = 'value';

        PageMetaFragment::where('key', $key)->delete();

        $page_meta->set($key, $value);
        $page_meta->save();

        $fragment = PageMetaFragment::where('key', $key)->first();
        $this->assertNull($fragment);

    }

    public function testToYaml()
    {
        $this->setUpData();
        $expected = $this->pageMeta->getAll();
        $yaml = $this->pageMeta->toYaml();
        $data = Yaml::parse($yaml);
        $this->assertEquals($expected, $data);
    }

}
