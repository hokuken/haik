<?php

use Hokuken\Haik\Page\PageData;

class PageDataTest extends TestCase {

    public function setUp()
    {
        $page_data = new PageData();
        $this->pageData = $page_data;
    }

    public function testHasKeyExists()
    {
        $this->pageData->set('key_exists', 'value');
        $result = $this->pageData->has('key_exists');
        $this->assertTrue($result);
    }
    
    public function testHasKeyNotExists()
    {
        $result = $this->pageData->has('key_not_exists');
        $this->assertFalse($result);
    }
    
    public function testGet()
    {
        $this->pageData->set('key', 'value');
        $result = $this->pageData->get('key');
        $this->assertEquals('value', $result);
    }
    
    public function testGetNotExists()
    {
        $result = $this->pageData->get('key_not_exists');
        $this->assertNull($result);
    }

    public function testGetWithDefaultValue()
    {
        $default_value = 'default Value';
        $result = $this->pageData->get('key_not_exists', $default_value);
        $this->assertEquals($default_value, $result);
    }
    
    public function testRemove()
    {
        $this->pageData->set('key_for_delete', 'value');
        $value_first = $this->pageData->get('key_for_delete');
        $this->pageData->remove('key_for_delete');
        $value_second = $this->pageData->get('key_for_delete');
        $this->assertEquals('value', $value_first);
        $this->assertNull($value_second);
    }
    
    public function testGetAll()
    {
        $this->pageData->set('key_first', 'value_first');
        $this->pageData->set('key_second', 'value_second');
        
        $result = $this->pageData->getAll();
        $this->assertEquals('value_first', $result['key_first']);
        $this->assertEquals('value_second', $result['key_second']);
    }
    
    public function testSetAll()
    {
        $data = array(
            'key_first'  => 'value_first',
            'key_second' => 'value_second',
        );
        $this->pageData->setAll($data);

        $result = $this->pageData->getAll();
        
        $this->assertEquals($data, $result);
    }
    
    public function testAppend()
    {
        $this->pageData->set('key', 'default_value');
        $this->pageData->append('key', ':append_value');
        $result = $this->pageData->get('key');
        $this->assertEquals('default_value:append_value', $result);
    }
    
    public function testPrepend()
    {
        $this->pageData->set('key', 'default_value');
        $this->pageData->prepend('key', 'prepend_value:');
        $result = $this->pageData->get('key');
        $this->assertEquals('prepend_value:default_value', $result);
    }
    
    public function testAppendToTheKeyNotExists()
    {
        $this->pageData->append('key_for_append', 'append_value');
        $result = $this->pageData->get('key_for_append');
        $this->assertEquals('append_value', $result);

        $this->pageData->append('key_for_append', ':second_value');
        $result = $this->pageData->get('key_for_append');
        $this->assertEquals('append_value:second_value', $result);
    }
    
    public function testPrependToTheKeyNotExists()
    {
        $this->pageData->append('key_for_prepend', 'prepend_value');
        $result = $this->pageData->get('key_for_prepend');
        $this->assertEquals('prepend_value', $result);

        $this->pageData->prepend('key_for_prepend', 'second_value:');
        $result = $this->pageData->get('key_for_prepend');
        $this->assertEquals('second_value:prepend_value', $result);
    }
    
    public function testSetOnce()
    {
        $this->pageData->setOnce('set_at_once', 'key', 'value');
        $result = $this->pageData->get('key');
        $this->pageData->setOnce('set_at_once', 'key', 'value_second');
        $result = $this->pageData->get('key');
        $this->assertEquals('value', $result);
    }
    
    public function testAppendOnce()
    {
        $this->pageData->appendOnce('append_at_once', 'key', 'append_value');
        $result = $this->pageData->get('key');
        $this->pageData->appendOnce('append_at_once', 'key', 'value_second');
        $result = $this->pageData->get('key');
        $this->assertEquals('append_value', $result);

        $this->pageData->appendOnce('append_another_context', 'key', ':value_third');
        $result = $this->pageData->get('key');
        $this->assertEquals('append_value:value_third', $result);
    }

    public function testPrependOnce()
    {
        $this->pageData->prependOnce('prepend_at_once', 'key', 'prepend_value');
        $result = $this->pageData->get('key');
        $this->pageData->prependOnce('prepend_at_once', 'key', 'value_second');
        $result = $this->pageData->get('key');
        $this->assertEquals('prepend_value', $result);

        $this->pageData->prependOnce('prepend_another_context', 'key', 'value_third:');
        $result = $this->pageData->get('key');
        $this->assertEquals('value_third:prepend_value', $result);
    }

}
