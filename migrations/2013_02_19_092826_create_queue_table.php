<?php

class Taskqueue_Create_Queue_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table(Config::get('task-queue::queues.table'), function($table)
		{
			$table->create();
			$table->increments('id');
			$table->text('queue');
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop(Config::get('task-queue::queues.table'));
	}

}