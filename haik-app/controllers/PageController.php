<?php

use Hokuken\HaikMarkdown\HaikMarkdown;

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
            $parser = new HaikMarkdown();
            $html = $parser->transform($page->body);
            return $html;
        }
        else
        {
            App::abort(404);
        }
    }

}
