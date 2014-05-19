<?php
class Page extends Eloquent {

    protected $table = 'haik_pages';

    protected $softDelete = true;

    protected $bodyIsParsed = false;
    protected $content = '';

    public static function boot()
    {
        parent::boot();

        $forget_cache = function($page)
        {
            $cache_key = $page->getCacheKey();
            if (Cache::has($cache_key))
            {
                Cache::forget($cache_key);
            }
        };

        // Forget cache on save
        static::saved($forget_cache);
        static::deleting($forget_cache);
        static::restored($forget_cache);
    }

    public function getCacheKey()
    {
        if ( ! $this->exists)
        {
            throw new \RuntimeException("This page is not exist: {$this->name}");
        }
        return "page.data:{$this->id}";
    }

    /**
     * Parse body to HTML
     *
     * @return $this for method chain
     */
    public function parseBody()
    {
        $this->content = Parser::transform($this->body);
        $this->bodyIsParsed = true;
        return $this;
    }

    /**
     * Get parsed content
     *
     * @return string HTML content
     * @throws \RuntimeException when parseBody is not called
     */
    public function getContentAttribute()
    {
        if ($this->bodyIsParsed)
        {
            return $this->content;
        }
        throw new \RuntimeException("This page body is not parsed.");
    }

    public function getMetaAttribute()
    {
        if ($this->exists)
        {
            return App::make('PageMeta', array($this));
        }
        return App::make('PageMeta', array($this, false));
    }

    public function getPriorityForSiteMap()
    {
        $name = $this->name;
        $layers = explode('/', $name);
        switch (count($layers))
        {
            case 1:
                $priority = 1.0;
                break;
            case 2:
                $priority = 0.8;
                break;
            case 3:
            default:
                $priority = 0.6;
        }
        return sprintf('%1.1f', $priority);
    }

}
