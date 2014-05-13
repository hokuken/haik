<?php
namespace Hokuken\Haik\Support;

interface DataBagInterface {

    /**
     * Determine data has the key
     *
     * @param string $key
     * @return boolean does data have key?
     */
    public function has($key);

    /**
     * Get data of specified key e.g group.value
     *
     * @param string $key
     * @param mixed $default_value when $key is not found then return this value
     * @return mixed specified data
     */
    public function get($key, $default_value = NULL);

    /**
     * Get all data of the page
     *
     * @return mixed data
     */
    public function getAll();

    /**
     * Set data of specified key e.g. group.value
     *
     * @param string $key
     * @param mixed $value
     * @return $this for method chain
     */
    public function set($key, $value);

    /**
     * Set or merge providing array to $data
     *
     * @param array $data data to set
     * @param boolean $overwrite flag of overwrite or merge data. Default is false(merge).
     * @return $this for method chain
     */
    public function setAll(array $array, $overwrite = false);

    /**
     * Remove data value of specified key
     *
     * @param string $key
     * @return $this for method chain
     */
    public function remove($key);

    /**
     * Remove all data
     *
     * @return $this for method chain
     */
    public function removeAll();

}
