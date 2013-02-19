<?php namespace TaskQueue;

use TaskQueue\Entities\Queue;
use TaskQueue\Repositories\QueueRepository;
use TaskQueue\Services\QueueValidator;
use Laravel\CLI\Command;

class Queues {

	protected $repository;

	public function __construct(QueueRepository $repository)
	{
		$this->repository = $repository;
	}

	public function repository()
	{
		return $this->repository;
	}

	public function add($task, $arguments = array(), $run = true)
	{
		$queue = new Queue($task, $arguments);

		$validation = $this->validate($queue);

		if($validation->passes())
		{
			if($run)
			{
				$this->runAsync($queue);
			}
			else
			{
				$this->repository->save($queue);
			}
		}
	}

	protected function validate(Queue $queue)
	{
		return new QueueValidator($queue);
	}

	public function runAll($async = false)
	{
		foreach($this->repository->getAll() as $queue)
		{
			if($async)
			{
				$this->runAsync($queue);
			}
			else
			{
				$this->run($queue);
			}
			$this->repository->delete($queue);
		}
	}

	public function run(Queue $queue)
	{
		$command = array($queue->task) + $queue->arguments;

		Command::run($command);
	}

	public function runAsync(Queue $queue)
	{
		$artisan = realpath(path('public') . '..').DS.'artisan';
		$base = PHP_BINDIR . "/php -f {$artisan} ";

		$command = $base . $queue->task . ' ' . implode(' ', $queue->arguments);

		exec("{$command} > /dev/null &");
	}

}