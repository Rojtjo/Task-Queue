<?php namespace TaskQueue\Services;

use TaskQueue\Entities\Queue;
use Laravel\Validator;

class QueueValidator {

	/**
	 * The validation rules
	 *
	 * @var array
	 */
	public static $rules = array(
		'task' => 'required'
	);

	/**
	 * The Validator object
	 *
	 * @var Validator
	 */
	public $validation;

	/**
	 * The entity we need to validate
	 *
	 * @var Queue
	 */
	public $input;

	/**
	 * Instantiate our validator
	 *
	 * @param Queue $queue The entity we need to validate
	 */
	public function __construct(Queue $queue)
	{
		$this->input      = $queue;
		$this->validation = Validator::make($this->input, static::$rules);
	}

	/**
	 * Check if the validation passes.
	 *
	 * @return boolean
	 */
	public function passes()
	{
		return $this->validation->passes();
	}

	/**
	 * Check if the validation fails.
	 *
	 * @return boolean
	 */
	public function fails()
	{
		return $this->validation->fails();
	}

}