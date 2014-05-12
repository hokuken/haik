<?php

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

        return View::make('page.show', $page_data);
    }

}
