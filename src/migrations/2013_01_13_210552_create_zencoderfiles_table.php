<?php

use Illuminate\Database\Migrations\Migration;

class CreateZencoderfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('zencoderfiles', function ($table)
		{
			$table->increments('id');
			$table->integer('job_id')->unsigned();
			$table->string('original_filename');
			$table->string('encoded_filename');
			$table->string('input_path', 255);
			$table->string('output_path', 255)->nullable();
			$table->integer('status')->unsigned();
			$table->integer('output_size')->nullable();
			$table->integer('duration')->nullable();
			$table->integer('width')->nullable();
			$table->integer('height')->nullable();
			$table->string('format')->nullable();
			$table->string('container')->nullable();


			$table->timestamps();
			$table->index('job_id');
			$table->unique(array('job_id'));

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('zencoderfiles');
	}

}