<?php
namespace Hokuken\Haik\Support;

use ArrayAccess;
use ArrayIterator;

class DataBag implements DataBagInterface, ArrayAccess {

    /** @var array data container */
    protected $container;

    public function __construct(array $data = array())
    {
        $this->container = $data;
    }

    /**
     * Determine data has the key
     *
     * @param string $key
     * @return boolean does data have key?
     */
    public function has($key)
    {
        $default_value = microtime();
        return $this->get($key, $default_value) !== $default_value;
    }

    /**
     * Get data of specified key e.g group.value
     *
     * @param string $key
     * @param mixed $default_value when $key is not found then return this value
     * @return mixed specified data
     */
    public function get($key, $default_value = null)
    {
        return array_get($this->container, $key, $default_value);
    }

    /**
     * Get all data of the page
     *
     * @return mixed data
     */
    public function getAll()
    {
        return $this->container;
    }

    /**
     * Set data of specified key e.g. group.value
     *
     * @param string $key
     * @param mixed $value
     * @return $this for method chain
     */
    public function set($key, $value)
    {
        array_set($this->container, $key, $value);

        return $this;
    }

    /**
     * Set or merge providing array to $data
     *
     * @param mixed $data data to set
     * @param boolean $overwrite flag of overwrite or merge data. Default is false(merge).
     * @return $this for method chain
     */
    public function setAll(array $data, $overwrite = false)
    {
        $data = array_merge(array_dot($this->container), array_dot($data));
        foreach ($data as $key => $value)
            $this->set($key, $value);

        return $this;
    }

    /**
     * Remove data value of specified key
     *
     * @param string $key
     * @return $this for method chain
     */
    public function remove($key)
    {
        if ($this->has($key)) unset($this->container[$key]);
        return $this;
    }

    /**
     * @throws \InvalidArgumentException when call with null $offset
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            throw new \InvalidArgumentException("offset: null not allowed.");
        }
        else
        {
            $this->set($offset, $value);
        }
    }

    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

	/**
	 * Get an iterator for the items.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->container);
	}

}
