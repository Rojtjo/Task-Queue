<?php namespace TaskQueue\Repositories;

use TaskQueue\Entities\Queue;
use Laravel\Database as DB;

class FluentQueueRepository implements QueueRepository {

	protected $queues;
	protected $fetch = false;

	public function getAll()
	{
		if( ! $this->fetch or is_null($this->queues))
		{
			$this->fetch = true;
			$this->queues = array();

			foreach(DB::table('TaskQueue')->get() as $queue)
			{
				$obj = $this->unserializeQueue($queue->queue);
				$obj->id = $queue->id;
				$this->queues[] = $obj;
			}
		}

		return $this->queues;
	}

	public function save(Queue $queue)
	{
		$this->fetch = false;
		$serialized = $this->serializeQueue($queue);

		return DB::table('TaskQueue')->insert(array(
			'queue'      => $serialized,
			'created_at' => new \DateTime,
			'updated_at' => new \DateTime,
		));
	}

	public function delete(Queue $queue)
	{
		$this->fetch = false;

		return DB::table('TaskQueue')->where_id($queue->id)->delete();
	}

	public function serializeQueue(Queue $queue)
	{
		return serialize($queue);
	}

	public function unserializeQueue($queue)
	{
		return unserialize($queue);
	}

}