<?php
namespace Hokuken\Haik\Site;

use DB;
use Hokuken\Haik\Support\FragmentBag;

class Config extends FragmentBag {

    /**
     * Create new Config container
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set Model class name to $model field
     */
    protected function setModel()
    {
        $this->model = 'ConfigFragment';
    }

    /**
     * Create instance of model
     */
    protected function createModel()
    {
        return new $this->model;
    }

}
