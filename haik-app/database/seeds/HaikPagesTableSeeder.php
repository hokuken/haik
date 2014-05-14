<?php

class HaikPagesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('haik_pages')->truncate();

        Page::create(array(
            'name' => 'FrontPage',
            'body' => '# Test Test',
        ));

        Page::create(array(
            'name' => 'Contact',
            'body' => '# Test Test',
        ));

        Page::create(array(
            'name' => 'SiteNav',
            'body' => '* Item' . "\n" .
                      '* Item' . "\n",
        ));

        Page::create(array(
            'name' => 'SiteFooter',
            'body' => '** Footer **',
        ));
    }
}
