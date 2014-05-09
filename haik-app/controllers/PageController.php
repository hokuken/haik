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
            return View::make('page.show', array('page' => $page->parseBody()));
        }
        else
        {
            App::abort(404);
        }
    }

}
