<?php
namespace Hokuken\Haik\Page;

use Page;
use PageMetaFragment;
use Symfony\Component\Yaml\Yaml;

class PageMeta implements PageMetaInterface {

    /** @var Page page ORM */
    protected $page;

    /** @var mixed meta data of the page*/
    protected $data;

    protected $isDirty;

    /**
     * Constructor
     *
     * @param Page $page page ORM
     * @param boolean $set_data when true then read and set data of the $page. Default is true
     */
    public function __construct(Page $page, $set_data = true)
    {
        $this->page = $page;
        $this->data = array();
        $this->isDirty = false;

        if ($set_data)
            $this->data = $this->read();
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Read meta data of the page
     *
     * @return mixed meta data array of the page
     */
    public function read()
    {
        $fragments = PageMetaFragment::where('haik_page_id', $this->page->id)->get();
        $data = array();
        foreach ($fragments as $fragment)
        {
            array_set($data, $fragment->key, $fragment->value);
        }
        $this->data = $data;
        $this->isDirty = false;
        return $this->data;
    }

    /**
     * Determine meta data has the key
     *
     * @param string $key
     * @return boolean does meta data have key?
     */
    public function has($key)
    {
        $default_value = microtime();
        return $this->get($key, $default_value) !== $default_value;
    }

    /**
     * Get meta data of specified key e.g group.value
     *
     * @param string $key
     * @param mixed $default_value when $key is not found then return this value
     * @return mixed specified meta data
     */
    public function get($key, $default_value = null)
    {
        return array_get($this->data, $key, $default_value);
    }

    /**
     * Get all meta data of the page
     *
     * @return mixed meta data
     */
    public function getAll()
    {
        return $this->data;
    }

    /**
     * Get all meta data as YAML
     *
     * @return string meta data formatted YAML
     */
    public function toYaml()
    {
        return Yaml::dump($this->data, 2);
    }

    /**
     * Set meta data of specified key e.g. group.value
     *
     * @param string $key
     * @param mixed $value
     * @return $this for method chain
     */
    public function set($key, $value)
    {
        if ($this->has($key))
        {
            if ($this->get($key) !== $value)
                $this->isDirty = true;
        }
        else
        {
            $this->isDirty = true;
        }
        array_set($this->data, $key, $value);

        return $this;
    }

    /**
     * Set or merge providing array to $data
     *
     * @param mixed $data meta data to set
     * @param boolean $overwrite flag of overwrite or merge data. Default is false(merge).
     */
    public function setAll($data, $overwrite = false)
    {
        if ( ! $overwrite)
        {
            $data = array_merge(array_dot($this->data), array_dot($data));
        }
        else
        {
            $data = array_dot($data);
        }
        foreach ($data as $key => $value)
            $this->set($key, $value);

        return $this;
    }

    /**
     * Remove meta data value of specified key
     *
     * @param string $key
     * @return $this for method chain
     */
    public function remove($key)
    {
        $saved_data = $this->data;

        array_forget($this->data, $key);
        if ($saved_data != $this->data)
        {
            $this->isDirty = true;
        }

        return $this;
    }

    /**
     * Determine this object is modified.
     *
     * @return boolean this object is modified?
     */
    public function isDirty()
    {
        return $this->isDirty;
    }

    /**
     * Save meta data
     *
     * @return $this for method chain
     * @throws \RuntimeException when page is not exist.
     */
    public function save()
    {
        if ( ! $this->page->id) throw new \RuntimeException("Page is not exist.");

        $this->delete();

        // create new fragments
        $data = array_dot($this->data);
        foreach ($data as $key => $value)
        {
            PageMetaFragment::create(array(
                'haik_page_id' => $this->page->id,
                'key' => $key,
                'value' => $value,
            ));
        }
    }

    /**
     * Delete meta data relating the page
     *
     * @return $this for method chain
     * @throws \RuntimeException when page is not exist.
     */
    public function delete()
    {
        if ( ! $this->page->id) throw new \RuntimeException("Page is not exist.");

        // clear existed fragments
        PageMetaFragment::where('haik_page_id', $this->page->id)->delete();
    }

}
