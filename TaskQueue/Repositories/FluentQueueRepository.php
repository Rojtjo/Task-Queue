<?php namespace TaskQueue\Repositories;

use TaskQueue\Entities\Queue;
use Laravel\Database as DB;
use Laravel\Config;

class FluentQueueRepository implements QueueRepository {

	/**
	 * All the queues
	 *
	 * @var array
	 */
	protected $queues;

	/**
	 * Have the queues been fetched yet? Or do they need to be refetched?
	 *
	 * @var boolean
	 */
	protected $fetched = false;

	/**
	 * {@inheritdoc}
	 *
	 */
	public function getAll()
	{
		if( ! $this->fetched or is_null($this->queues))
		{
			$this->fetched = true;
			$this->queues = array();

			foreach(DB::table(Config::get('task-queue::queues.table'))->get() as $queue)
			{
				$obj = $this->unserializeQueue($queue->queue);
				$obj->id = $queue->id;
				$this->queues[] = $obj;
			}
		}

		return $this->queues;
	}

	/**
	 * {@inheritdoc}
	 *
	 */
	public function get($id)
	{
		if( ! $this->fetched or is_null($this->queues))
		{
			$queue = DB::table(Config::get('task-queue::queues.table'))->where_id($id)->first();
			$obj = $this->unserializeQueue($queue->queue);
			$obj->id = $queue->id;

			return $obj;
		}
		else
		{
			foreach($this->queues as $queue)
			{
				if($queue->id == $id)
				{
					return $queue;
				}
			}
		}
	}

	/**
	 * {@inheritdoc}
	 *
	 */
	public function save(Queue $queue)
	{
		$this->fetched = false;
		$serialized = $this->serializeQueue($queue);

		$id = DB::table(Config::get('task-queue::queues.table'))->insert_get_id(array(
			'queue'      => $serialized,
			'created_at' => new \DateTime,
			'updated_at' => new \DateTime,
		));

		if($id)
		{
			$queue->id = $id;

			return $queue;
		}

		return false;
	}

	/**
	 * {@inheritdoc}
	 *
	 */
	public function delete(Queue $queue)
	{
		$this->fetched = false;

		return DB::table(Config::get('task-queue::queues.table'))->where_id($queue->id)->delete();
	}

	/**
	 * {@inheritdoc}
	 *
	 */
	public function serializeQueue(Queue $queue)
	{
		return serialize($queue);
	}

	/**
	 * {@inheritdoc}
	 *
	 */
	public function unserializeQueue($queue)
	{
		return unserialize($queue);
	}

}