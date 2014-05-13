<?php
namespace Hokuken\Haik\Page;

use Page;
use PageMetaFragment;
use Symfony\Component\Yaml\Yaml;
use Hokuken\Haik\Support\FragmentBag;

class PageMeta extends FragmentBag implements PageMetaInterface {

    /** @var Page page ORM */
    protected $page;

    /**
     * Constructor
     *
     * @param Page $page page ORM
     * @param boolean $set_data when true then read and set data of the $page. Default is true
     */
    public function __construct(Page $page, $set_data = true)
    {
        parent::__construct();
        $this->page = $page;

        if ($set_data)
            $this->data = $this->read();
    }

    /**
     * Set Model class name to $model field
     */
    protected function setModel()
    {
        $this->model = 'PageMetaFragment';
    }

    /**
     * Create instance of model
     */
    protected function createModel()
    {
        $model = $this->model;
        $instance = new $model;
        $page = $this->getPage();
        if ($page->exists)
            $instance->haik_page_id = $page->id;

        return $instance;
    }

    /**
     * Save model
     */
    protected function saveModel($fragment)
    {
        if ($this->getPage()->exists)
            $fragment->save();
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
     * Get all meta data as YAML
     *
     * @return string meta data formatted YAML
     */
    public function toYaml()
    {
        return Yaml::dump($this->getAll(), 2);
    }

}
