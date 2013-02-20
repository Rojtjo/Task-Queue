<?php namespace TaskQueue;

use TaskQueue\Entities\Queue;
use TaskQueue\Repositories\QueueRepository;
use TaskQueue\Services\QueueValidator;
use Laravel\CLI\Command;
use Laravel\Bundle;
use Laravel\Config;
use Laravel\URL;

class Queues {

	/**
	 * Our repository instance
	 *
	 * @var QueueRepository
	 */
	protected $repository;

	/**
	 * Instantiate the queues class
	 * @param QueueRepository $repository [description]
	 */
	public function __construct(QueueRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * Repository instance
	 *
	 * @return QueueRepository
	 */
	public function repository()
	{
		return $this->repository;
	}

	/**
	 * Add a task to the queue
	 * @param string  $task      The name of the task
	 * @param array   $arguments The arguments for the task
	 * @param boolean $run       Wether it should run immediately or get queued
	 */
	public function add($task, $arguments = array(), $run = true)
	{
		$queue = new Queue($task, $arguments);

		$validation = $this->validate($queue);

		if($validation->passes())
		{
			$queue = $this->repository()->save($queue);

			if($queue)
			{
				if($run)
				{
					$this->runAsync($queue);
				}
			}
		}
	}

	/**
	 * Get a queued task by id
	 *
	 * @param  integer $id The id of the queued task
	 *
	 * @return Queue
	 */
	public function get($id)
	{
		return $this->repository()->get($id);
	}

	/**
	 * Validate the Queue
	 *
	 * @param  Queue  $queue The queue we want to validate
	 *
	 * @return QueueValidator
	 */
	protected function validate(Queue $queue)
	{
		return new QueueValidator($queue);
	}

	/**
	 * Run all tasks
	 *
	 * @param  boolean $async Wether to run the tasks synchronous or asynchronous
	 *
	 * @return void
	 */
	public function runAll($async = false)
	{
		foreach($this->repository()->getAll() as $queue)
		{
			if($async)
			{
				$this->runAsync($queue);
			}
			else
			{
				$this->run($queue);
			}
		}
	}

	/**
	 * Run a queued task
	 *
	 * @param  Queue  $queue The queue we want to run
	 *
	 * @return void
	 */
	public function run(Queue $queue)
	{
		$command = array_merge((array) $queue->task, $queue->arguments);

		Command::run($command);

		$this->repository()->delete($queue);
	}

	/**
	 * Run a queued task asynchronous
	 *
	 * @param  Queue  $queue The queue we want to run
	 *
	 * @return void
	 */
	public function runAsync(Queue $queue)
	{
		$url   = URL::to(Bundle::get('task-queue.handles') . '/' . $queue->id);
		$parts = parse_url($url);
		$port  = isset($parts['port']) ? $parts['port'] : 80;

		$fp = fsockopen($parts['host'], $port, $errno, $errstr, 10);

		$out = "GET ".$parts['path']." HTTP/1.1\r\n";
		$out .= "Host: ".$parts['host']."\r\n";
		$out .= "Connection: Close\r\n\r\n";

		fwrite($fp, $out);
		fclose($fp);
	}

}