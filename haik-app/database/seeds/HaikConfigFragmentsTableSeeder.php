<?php

class HaikConfigFragmentsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('haik_config_fragments')->truncate();

        ConfigFragment::create(array(
            'key' => 'site.title',
            'value' => 'ha::::k'
        ));

        ConfigFragment::create(array(
            'key' => 'site.author',
            'value' => 'issa'
        ));
    }
}
