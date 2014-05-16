<?php

class EditPluginControllerTest extends TestCase {

    public function setUp()
    {
        parent::setUp();

        if (Page::withTrashed()->where('name', 'Contact')->count() === 0)
        {
            $page = new Page();
            $page->name = 'Contact';
            $page->body = '# Contact';
            $page->save();
        }
    }

    public function testShowForm()
    {
        $crawler = $this->client->request('GET', '/cmd/edit/FrontPage');
        $this->assertTrue($this->client->getResponse()->isOk());
        $this->assertViewHas('page');
    }

    public function testDelete()
    {
        $pagename = 'Contact';
        $crawler = $this->client->request('GET', '/cmd/delete/' . $pagename);
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertNull(Page::where('name', $pagename)->first());

        $this->assertTrue( !! Page::withTrashed()->where('name', $pagename)->first());
    }

    public function testForceDelete()
    {
        $pagename = 'Contact';
        $crawler = $this->client->request('POST', '/cmd/delete', array(
            'name' => $pagename
        ));
        $this->assertTrue($this->client->getResponse()->isRedirect());
        $this->assertNull(Page::where('name', $pagename)->first());

        $this->assertNull(Page::withTrashed()->where('name', $pagename)->first());
    }

}
