<?php

class HaikConfigFragmentsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('haik_config_fragments')->truncate();

        ConfigFragment::create(array(
            'key' => 'title',
            'value' => 'ha::::k'
        ));

        ConfigFragment::create(array(
            'key' => 'author',
            'value' => 'issa'
        ));

        ConfigFragment::create(array(
            'key' => 'theme.name',
            'value' => 'ikk'
        ));
    }
}
