<?php

use Carbon\Carbon;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Hokuken\Haik\Support\DataBag;

class PageController extends BaseController {

    public function show($page)
    {
        if ($page === '/')
        {
            $page = Page::first();
        }
        else
        {
            $page = Page::where('name', $page)->first();
        }

        if ($page)
        {
            App::instance('page.current', $page);

            return $this->render();

        }
        else
        {
            App::abort(404);
        }
    }

    protected function render()
    {
        $theme_repository_path = storage_path() . '/themes';
        View::addLocation($theme_repository_path);
        View::addNamespace('themes', $theme_repository_path);

        $page = App::make('page.current');
        $cache_key = $page->getCacheKey();

        if (Cache::has($cache_key))
        {
            $data = Cache::get($cache_key);
            $view = $data['view'];
            return View::make($view, $data);
        }

        $page_data = new DataBag( array(
            'site' => Site::getAll(),
        ));

        // Merge page meta data
        $page_meta = $page->meta;
        $page_data->setAll($page_meta->getAll());

        // Parse body to HTML
        $page->parseBody();
        $page_data['page'] = $page->name;
        $page_data['content'] = $page->content;
        $page_data['updated_at'] = $page->updated_at->format('Y年m月d日');
        $page_data['title'] = $this->getTitle();
        
        $page_data['messages'] = array(
            'edit_link' => $page_data['title'] . 'の編集'
        );

        $theme = $page->meta->get('theme.name', Site::get('theme.name'));
        $theme_path = $theme_repository_path . '/' . $theme;

        // Read theme config from theme.yml
        // !TODO: Put default config array
        try {
            $theme_config = Yaml::parse(file_get_contents($theme_path . '/theme.yml'));
        }
        catch (ParseException $e) {
            $theme_config = array();
        }

        $theme_config['base_url'] = url('haik-contents/themes/'.$theme);
        $theme_config['css'] = $theme_config['base_url'].'/'.$theme_config['css'];

        $page_data['theme'] = $theme_config;
        
        $theme_template_name = $page->meta->get('theme.template', 'default');
        if (isset($theme_config['templates'][$theme_template_name]['filename']) && 
              file_exists($theme_path.'/'.$theme_config['templates'][$theme_template_name]['filename']))
        {
            $view = "themes::{$theme}." . basename($theme_config['templates'][$theme_template_name]['filename'], '.html');
        }
        else
        {
            $view = "themes::{$theme}.theme";
        }
        $page_data['view'] = $view;

        // Cache page data
        $data = $page_data->getAll();
        $expiresAt = Carbon::now()->addDay();
        Cache::add($cache_key, $data, $expiresAt);

        return View::make($view, $data);
    }
    
    protected function getTitle()
    {
        $page = App::make('page.current');
        
        $page_title = $page->meta->get('title', $page->name);
        $site_title = Site::get('title', '');
        if ($page->name === Config::get('haik.page.default'))
        {
            // if default page set page title of page meta first
            $page_title = ($page_title !== $page->name) ? $page_title : $site_title;
        }
        else
        {
            $page_title = $page_title . ' - ' . $site_title;
        }
        
        return $page_title;
    }

}
