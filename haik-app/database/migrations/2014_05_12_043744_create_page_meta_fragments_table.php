<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageMetaFragmentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('haik_page_meta_fragments', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('haik_page_id')->unsigned();
            $table->string('key');
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::drop('haik_page_meta_fragments');
    }

}
