<?php namespace TaskQueue\Entities;

class Queue {

	/**
	 * The queued task
	 *
	 * @var string
	 */
	public $task;

	/**
	 * The arguments for the task
	 *
	 * @var array
	 */
	public $arguments = array();

	/**
	 * Instantiate the Queue object
	 *
	 * @param string $task      The task we want to queue
	 * @param array  $arguments The arguments for the task
	 */
	public function __construct($task, $arguments = array())
	{
		$this->task      = $task;
		$this->arguments = $arguments;
	}

}