<?php

class HaikPageMetaFragmentsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('haik_page_meta_fragments')->truncate();

        $pages = DB::table('haik_pages')->get();
        foreach ($pages as $page)
        {
            switch ($page->name)
            {
                case 'FrontPage':
                    $this->seedFrontPageMeta($page);
                    break;
                case 'Contact':
                    $this->seedContactPageMeta($page);
            }
        }
    }

    protected function seedFrontPageMeta($page)
    {
    }

    protected function seedContactPageMeta($page)
    {
        PageMetaFragment::create(array(
            'haik_page_id' => $page->id,
            'key' => 'title',
            'value' => 'お問い合わせ'
        ));
    }
}
