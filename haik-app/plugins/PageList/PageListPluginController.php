<?php
namespace Hokuken\Haik\Plugin\PageList;

use BaseController;
use Config;
use Response;
use View;
use Page;
use Site;

class PageListPluginController extends BaseController {

    protected function getPages()
    {
        $pages = Page::all();
        return $pages;
    }

    /**
     * Show page list
     *
     * @param string $format html or json
     * @return View
     */
    public function show($format = 'html')
    {
        $pages = $this->getPages();

        if ($format === 'json')
        {
            return Response::json($pages->toArray());
        }

        return View::make('page.list')->with(array(
            'haik'  => Config::get('haik'),
            'site'  => Site::getAll(),
            'pages' => $pages,
        ));
    }

    /**
     * Show site map as XML
     *
     * @return View
     */
    public function siteMap()
    {
        $pages = array();

        foreach ($this->getPages() as $i => $page)
        {
            $pages[$i] = $page->toArray();
            $pages[$i]['priority'] = $page->getPriorityForSiteMap();
        }

        return View::make('page.sitemap_xml')->with(array(
            'haik'  => Config::get('haik'),
            'pages' => $pages
        ));
    }

}
