<?php
namespace Hokuken\Haik\Site;

use DB;
use ConfigFragment;
use Hokuken\Haik\Support\DataBag;

class Config extends DataBag {

    /** @var array of ConfigFragment to delete */
    protected $removedFragments;

    /**
     * Create new Config container
     */
    public function __construct()
    {
        parent::__construct();
        $this->removedFragments = array();
        $this->read();
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
            if ($value instanceof ConfigFragment)
                $value = $value->value;
            array_set($data, $key, $value);
        }
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        if ($value instanceof ConfigFragment)
        {
            return parent::set($key, $value);
        }

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
                $fragment = new ConfigFragment();
                $fragment->key = $key;
            }
        }
        $fragment->value = (string)$value;

        parent::set($key, $fragment);

        return $this;
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
        $this->removedFragments = array();

        $fragments = ConfigFragment::all();

        foreach ($fragments as $fragment)
        {
            $this->set($fragment->key, $fragment);
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
        $data = array_dot($this->container);
        $removed_data = array_dot($this->removedFragments);

        DB::transaction(function() use ($data, $removed_data)
        {
            foreach ($data as $key => $fragment)
            {
                if ( ! $fragment instanceof ConfigFragment or ! $fragment->getDirty()) continue;
                $fragment->save();
            }
    
            foreach ($removed_data as $key => $fragment)
            {
                if ( ! $fragment instanceof ConfigFragment) continue;
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
        $removed_data = array_dot($this->removedFragments);
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
        DB::table(with(new ConfigFragment())->getTable())->delete();
        return $this;
    }

}
