<?php
namespace Hokuken\Haik\Page;

interface PageMetaInterface {

    /**
     * Get page name
     *
     * @return string page name
     */
    public function getPage();

    /**
     * Read meta data of the page
     *
     * @return mixed meta data array of the page
     */
    public function read();

    /**
     * Determine meta data has the key
     *
     * @param string $key
     * @return boolean does meta data have key?
     */
    public function has($key);

    /**
     * Get meta data of specified key e.g group.value
     *
     * @param string $key
     * @param mixed $default_value when $key is not found then return this value
     * @return mixed specified meta data
     */
    public function get($key, $default_value = NULL);

    /**
     * Get all meta data of the page
     *
     * @return mixed meta data
     */
    public function getAll();

    /**
     * Get all meta data as YAML
     *
     * @return string meta data formatted YAML
     */
    public function toYaml();

    /**
     * Set meta data of specified key e.g. group.value
     *
     * @param string $key
     * @param mixed $value
     * @return $this for method chain
     */
    public function set($key, $value);

    /**
     * Set or merge providing array to $data
     *
     * @param mixed $data meta data to set
     * @param boolean $overwrite flag of overwrite or merge data. Default is false(merge).
     */
    public function setAll($array, $overwrite = false);

    /**
     * Remove meta data value of specified key
     *
     * @param string $key
     * @return $this for method chain
     */
    public function remove($key);

    /**
     * Determine this object is modified.
     *
     * @return boolean this object is modified?
     */
    public function isDirty();

    /**
     * Save meta data
     *
     * @return save is successed?
     */
    public function save();

    /**
     * Delete meta data relating the page
     *
     * @return delete is successed?
     */
    public function delete();

}
