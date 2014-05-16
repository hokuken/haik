<?php
namespace Hokuken\Haik\Plugin\Edit;

use Cache;
use Config;
use Input;
use Redirect;
use Request;
use View;
use BaseController;
use Page;

class EditPluginController extends BaseController {

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
            'page' => $page,
            'page_meta' => $page->meta->getAll(),
            'page_meta_yaml' => $page->meta->toYaml(),
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

        if ($page->trashed())
        {
            $page->restore();
        }

        $page_yaml = Input::get('page_meta_yaml', '');
        $page->meta->setYaml($page_yaml)->save();

        if (Config::get('haik.page.default') === $page->name)
        {
            $pagename = '';
        }
        return $this->redirectTo($page);
    }

    /**
     * Delete page.
     * When GET method: soft delete
     * When POST method: force delete page that is trashed
     *
     * @return Redirect to /
     * @throws \InvalidArgumentException when pagename cannot be deleted
     */
    public function delete($pagename = '')
    {
        $force = false;
        if (Request::isMethod('post'))
        {
            $force = true;
            $pagename = Input::get('name', '');
        }
        $page = $this->getPage($pagename);

        if ( ! $page->exists OR $page->name === Config::get('haik.page.default'))
        {
            throw new \InvalidArgumentException("Cannot delete specified page: {$page->name}");
        }

        if ($force)
        {
            if ( ! $page->trashed())
            {
                throw new \InvalidArgumentException("Cannot force delete specified page: {$page->name}");
            }
            $page->forceDelete();
        }
        else
        {
            $page->delete();
        }
        return $this->redirectTo();
    }

    protected function getPage($pagename = '')
    {
        if ($pagename === '')
        {
            $pagename = Config::get('haik.page.default');
        }
        $page = Page::withTrashed()->where('name', $pagename)->first();

        if ( ! $page)
        {
            $page = new Page();
            $page->name = $pagename;
        }
        return $page;
    }

    protected function redirectTo(Page $page = null)
    {
        $default_page = Config::get('haik.page.default');
        if ($page === null OR $page->name === $default_page)
        {
            $pagename = '';
        }
        else
        {
            $pagename = $page->name;
        }
        return Redirect::route('show_page', $pagename);
    }
}
