<?php
namespace Hokuken\Haik\Support;

use DB;
use Hokuken\Haik\Support\DataBag;

abstract class FragmentBag extends DataBag {

    /** @var array of Fragments to delete */
    protected $removedFragments;

    /** @var string class name of Model */
    protected $model;

    /**
     * Create new Config container
     */
    public function __construct()
    {
        parent::__construct();
        $this->removedFragments = new DataBag();
        $this->setModel();
        $this->read();
    }

    /**
     * Set Model class name to $model field
     */
    abstract protected function setModel();

    /**
     * Create instance of model
     */
    abstract protected function createModel();

    /**
     * Save model
     */
    protected function saveModel($fragment)
    {
        $fragment->save();
    }

    /**
     * @inheritdoc
     */
    public function get($key, $default_value = null)
    {
        $fragment = $this->getFragment($key);
        if ($fragment !== null)
        {
            return $fragment->value;
        }

        return $default_value;
    }

    protected function getFragment($key)
    {
        return array_get($this->container, $key, null);
    }

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        $data = array();
        $container = array_dot($this->container);
        foreach ($container as $key => $value)
        {
            if (is_a($value, $this->model))
            {
                $value = $value->value;
                array_set($data, $key, $value);
            }
        }
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        if ($this->has($key))
        {
            $fragment = $this->getFragment($key);
        }
        else
        {
            if (isset($this->removeFragments[$key]))
            {
                $fragment = $this->removeFragments[$key];
                unset($this->removeFragments[$key]);
            }
            else
            {
                $fragment = $this->createModel();
                $fragment->key = $key;
            }
        }
        $fragment->value = (string)$value;

        parent::set($key, $fragment);

        return $this;
    }

    public function setFragment($key, $fragment)
    {
        parent::set($key, $fragment);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        if ($this->has($key))
        {
            $this->removedFragments[$key] = $this->getFragment($key);
            parent::remove($key);
        }
        return $this;
    }

    public function removeAll()
    {
        $data = array_dot($this->container);
        foreach ($data as $key => $value)
        {
            $this->remove($key);
        }
        return $this;
    }

    /**
     * Read meta data of the page
     *
     * @return $this for method chain
     */
    public function read()
    {
        $this->removeAll();
        $this->removedFragments = new DataBag();

        $model = $this->model;
        $fragments = $model::all();

        foreach ($fragments as $fragment)
        {
            $this->setFragment($fragment->key, $fragment);
        }

        return $this;
    }

    /**
     * Save data
     *
     * @return $this for method chain
     */
    public function save()
    {
        $self = $this;
        $data = array_dot($this->container);
        $removed_data = array_dot($this->removedFragments->getAll());

        DB::transaction(function() use ($self, $data, $removed_data)
        {
            foreach ($data as $key => $fragment)
            {
                if ( ! is_a($fragment, $self->model) or ! $fragment->getDirty()) continue;
                $self->saveModel($fragment);
            }
    
            foreach ($removed_data as $key => $fragment)
            {
                if ( ! is_a($fragment, $self->model)) continue;
                $fragment->delete();
            }
        });
    }

    /**
     * Delete data prepared to delete
     *
     * @return $this for method chain
     */
    public function delete()
    {
        $removed_data = array_dot($this->removedFragments->getAll());
        DB::transaction(function() use ($removed_data)
        {
            foreach ($removed_data as $fragment)
            {
                $fragment->delete();
            }
        });
        return $this;
    }

    /**
     * Delete all config of site
     *
     * @return $this for method chain
     */
    public function deleteAll()
    {
        DB::table($this->createModel()->getTable())->delete();
        return $this;
    }

}
