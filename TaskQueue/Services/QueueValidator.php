<?php namespace TaskQueue\Services;

use TaskQueue\Entities\Queue;
use Laravel\Validator;

class QueueValidator {

	public static $rules = array(
		'task' => 'required'
	);

	public $validation;
	public $input;

	public function __construct(Queue $queue)
	{
		$this->input      = $queue;
		$this->validation = Validator::make($this->input, static::$rules);
	}

	public function passes()
	{
		return $this->validation->passes();
	}

	public function fails()
	{
		return $this->validation->fails();
	}

}