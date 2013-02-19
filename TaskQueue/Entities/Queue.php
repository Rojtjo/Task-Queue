<?php namespace TaskQueue\Entities;

class Queue {

	public $task;
	public $arguments = array();

	public function __construct($task, $arguments = array())
	{
		$this->task      = $task;
		$this->arguments = $arguments;
	}

}