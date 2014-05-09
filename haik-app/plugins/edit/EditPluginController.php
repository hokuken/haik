<?php
namespace Hokuken\Haik\Plugin\Edit;

use Config;
use View;
use Input;
use Redirect;
use BaseController;
use Page;

class Controller extends BaseController {

    /**
     * Show page edit form
     *
     * @param string $page page name to edit
     * @return View of edit form
     */
    public function showForm($page = '')
    {
        $page = $this->getPage($page);

        return View::make('page.edit', array(
            'page' => $page
        ));
    }

    /**
     * Save page
     *
     * @return Redirect to specified page
     * @throws \InvalidArgumentException when pagename cannot be get
     */
    public function save()
    {
        $pagename = Input::get('name', '');
        if ($pagename === '') throw new \InvalidArgumentException("This request not found page name");

        $page = $this->getPage($pagename);

        if ($page->body_version > Input::get('body_version', 0))
        {
            // TODO: collision detection
        }
        $page->body = Input::get('body', '');
        $page->body_version = $page->body_version + 1;
        $page->save();
        return Redirect::route('show_page', $pagename);
    }

    protected function getPage($pagename = '')
    {
        if ($pagename === '')
        {
            $pagename = Config::get('haik.page.default');
        }
        $page = Page::where('name', $pagename)->first();

        if ( ! $page)
        {
            $page = new Page();
            $page->name = $pagename;
        }
        return $page;
    }

}
