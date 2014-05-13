<?php
use Hokuken\Haik\Support\DataBag;

class DataBagTest extends TestCase {

    public function setUp()
    {
        $bag = new DataBag();
        $this->bag = $bag;
    }

    public function testHasKeyExists()
    {
        $this->bag->set('key_exists', 'value');
        $result = $this->bag->has('key_exists');
        $this->assertTrue($result);
    }

    public function testHasKeyNotExists()
    {
        $result = $this->bag->has('key_not_exists');
        $this->assertFalse($result);
    }

    public function testGet()
    {
        $this->bag->set('key', 'value');
        $result = $this->bag->get('key');
        $this->assertEquals('value', $result);
    }

    public function testGetNotExists()
    {
        $result = $this->bag->get('key_not_exists');
        $this->assertNull($result);
    }

    public function testGetWithDefaultValue()
    {
        $default_value = 'default Value';
        $result = $this->bag->get('key_not_exists', $default_value);
        $this->assertEquals($default_value, $result);
    }

    public function testRemove()
    {
        $this->bag->set('key_for_delete', 'value');
        $value_first = $this->bag->get('key_for_delete');
        $this->bag->remove('key_for_delete');
        $value_second = $this->bag->get('key_for_delete');
        $this->assertEquals('value', $value_first);
        $this->assertNull($value_second);
    }

    public function testGetAll()
    {
        $this->bag->set('key_first', 'value_first');
        $this->bag->set('key_second', 'value_second');
        
        $result = $this->bag->getAll();
        $this->assertEquals('value_first', $result['key_first']);
        $this->assertEquals('value_second', $result['key_second']);
    }

    public function testSetAll()
    {
        $data = array(
            'key_first'  => 'value_first',
            'key_second' => 'value_second',
        );
        $this->bag->setAll($data);

        $result = $this->bag->getAll();
        
        $this->assertEquals($data, $result);
    }

}
