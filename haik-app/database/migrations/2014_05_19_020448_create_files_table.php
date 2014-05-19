<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('haik_files', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->string('filepath');
            $table->string('type');
            $table->string('mime_type')->nullable();
            $table->integer('size')->unsigined()->default(0);
            $table->string('dimensions')->nullable();
            $table->boolean('starred')->default(false);
            $table->boolean('publicity')->default(true);
            $table->text('note')->nullable();
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
        Schema::drop('haik_files');
	}

}
