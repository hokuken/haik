<?php

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

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
        $page = App::make('page.current');

        $page_data = array(
            'title' => $page->name
        );

        // Merge page meta data
        $page_meta = $page->meta;
        foreach (array_dot($page_meta->getAll()) as $key => $value)
        {
            $key = str_replace('.', '_', $key);
            $page_data[$key] = $value;
        }

        // Parse body to HTML
        $page->parseBody();
        $page_data['page'] = $page->name;
        $page_data['content'] = $page->content;
        $page_data['updated_at'] = $page->updated_at->format('Y年m月d日');

        $page_data['messages'] = array(
            'edit_link' => $page_data['title'] . 'の編集'
        );
        

        $theme_repository_path = storage_path() . '/themes';
        View::addLocation($theme_repository_path);
        View::addNamespace('themes', $theme_repository_path);

        $theme = 'ikk';
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

        $view = "themes::{$theme}.theme";

        return View::make($view, $page_data);
    }

}
