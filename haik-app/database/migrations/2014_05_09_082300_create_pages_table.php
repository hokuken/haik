<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('haik_pages', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('name')->unique();
            $table->text('body');
            $table->integer('body_version')->unsigined()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('haik_pages');
    }

}
