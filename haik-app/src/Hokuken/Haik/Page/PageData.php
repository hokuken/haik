<?php
namespace Hokuken\Haik\Page;

use Hokuken\Haik\Support\DataBag;

class PageData extends DataBag implements PageDataInterface {

    /** @var array of page data context related *_once methods*/
    protected $contexts;

    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->contexts = array();
    }

    /**
     * @inheritdoc
     */
    public function setOnce($context, $key, $value)
    {
        if ($this->contextExists($context)) return $this;
        
        $this->setContext($context);
        $this->set($key, $value);
        return $this;
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
        if ( ! is_string($value))
            throw new \InvalidArgumentException("This method Permit only string to pass arg #2.");
        $current_value = $this->get($key, '');
        if ( ! is_string($current_value))
            throw new \InvalidArgumentException("Cannot append to this offset: $key. Value is not string.");

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
        if ( ! is_string($value))
            throw new \InvalidArgumentException("This method Permit only string to pass arg #2.");
        $current_value = $this->get($key, '');
        if ( ! is_string($current_value))
            throw new \InvalidArgumentException("Cannot prepend to this offset: $key. Value is not string.");

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
    public function toJson($options = 0)
    {
        return json_encode($this->data, $options);
    }

}
