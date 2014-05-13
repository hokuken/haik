<?php
namespace Hokuken\Haik\Page;

class PageData implements PageDataInterface {

    /** @var array of page data */
    protected $data;

    /** @var array of page data context related *_once methods*/
    protected $contexts;

    public function __construct(array $data = array())
    {
        $this->data = $data;
        $this->contexts = array();
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        array_set($this->data, $key, $value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setOnce($context, $key, $value)
    {
        if ($this->contextExists($context)) return;
        
        $this->setContext($context);
        $this->data[$key] = $value;
    }

    /**
     * @inheritdoc
     */
    public function setAll(array $data)
    {
        $data = array_merge(array_dot($this->data), array_dot($data));
        foreach ($data as $key => $value)
            $this->set($key, $value);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function get($key, $default_value = null)
    {
        return array_get($this->data, $key, $default_value);
    }

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        return $this->data;
    }
    
    /**
     * @inheritdoc
     */
    public function has($key)
    {
        $default_value = microtime();
        return $this->get($key, $default_value) !== $default_value;
    }

    /**
     * Determine passed $context is already exist
     */
    protected function contextExists($context)
    {
        return array_key_exists($context, $this->contexts);
    }

    protected function setContext($context)
    {
        $this->contexts[$context] = true;
    }

    /**
     * @inhritdoc
     */
    public function append($key, $value)
    {
        $current_value = $this->has($key) ? $this->get($key) : '';
        $this->set($key, $current_value . $value);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function appendOnce($context, $key, $value)
    {
        if ($this->contextExists($context)) return;
        $this->setContext($context);
        $this->append($key, $value);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function prepend($key, $value)
    {
        $current_value = $this->has($key) ? $this->get($key) : '';
        $this->set($key, $value . $current_value);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function prependOnce($context, $key, $value)
    {
        if ($this->contextExists($context)) return;
        $this->setContext($context);
        $this->prepend($key, $value);
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        if ($this->has($key)) unset($this->data[$key]);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toJson($options = 0)
    {
        return json_encode($this->data, $options);
    }

}
