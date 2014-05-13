<?php
namespace Hokuken\Haik\Page;

/**
 * Manage page data for view.
 */
interface PageDataInterface {

    /**
     * Set page data
     *
     * @param string $key key of data
     * @param mixed $value value of key
     * @return $this for method chain
     */
    public function set($key, $value);

    /**
     * Set page data only once
     *
     * @param string $context context of data if context is already exist then data is not set
     * @param string $key key of data
     * @param mixed $value value of key
     * @return $this for method chain
     */
    public function setOnce($context, $key, $value);

    /**
     * Set data array
     *
     * @param array $data
     * @return $this for method chain
     */
    public function setAll(array $data);

    /**
     * Get page data
     *
     * @param string $key key of data
     * @param mixed $default_value when $key is not exist, then return this value.
     * @return mixed data of $key.
     */
    public function get($key, $default_value = null);

    /**
     * Get all page data
     *
     * @return array data of page
     */
    public function getAll();
    
    /**
     * Determine page has value of $key.
     *
     * @param string $key key of data
     * @return boolean data is exist?
     */
    public function has($key);

    /**
     * Append string to page data
     *
     * @param string $key key of data
     * @param string $value append data
     * @return $this for method chain
     * @throws \InvalidArgumentException when $value or data[$key] is not string
     */
    public function append($key, $value);

    /**
     * Append string to page data only once
     *
     * @param string $context context of data if context is already exist then data is not set
     * @param string $key key of data
     * @param string $value append data
     * @return $this for method chain
     * @throws \InvalidArgumentException when $value or data[$key] is not string
     */
    public function appendOnce($context, $key, $value);

    /**
     * Prepend string to page data
     *
     * @param string $key key of data
     * @param string $value append data
     * @return $this for method chain
     * @throws \InvalidArgumentException when $value or data[$key] is not string
     */
    public function prepend($key, $value);

    /**
     * Prepend string to page data only once
     *
     * @param string $context context of data if context is already exist then data is not set
     * @param string $key key of data
     * @param string $value append data
     * @return $this for method chain
     * @throws \InvalidArgumentException when $value or data[$key] is not string
     */
    public function prependOnce($context, $key, $value);
    
    /**
     * Remove page data of $key
     *
     * @params string $key key of data
     * @return $this for method chain
     */
    public function remove($key);


    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0);

}
