<?php namespace TaskQueue\Repositories;

use TaskQueue\Entities\Queue;

interface QueueRepository {

	/**
	 * Get all queues from the data source
	 *
	 * @return array
	 */
	public function getAll();

	/**
	 * Get a queue by id
	 *
	 * @param  integer $id The id of the queue
	 *
	 * @return Queue
	 */
	public function get($id);

	/**
	 * Save the queue
	 *
	 * @param  Queue  $queue The queue we want to save
	 *
	 * @return boolean|Queue
	 */
	public function save(Queue $queue);

	/**
	 * Delete the queue
	 *
	 * @param  Queue  $queue The queue we want to delete
	 *
	 * @return boolean
	 */
	public function delete(Queue $queue);

	/**
	 * Serialize the Queue object
	 *
	 * @param  Queue  $queue
	 *
	 * @return string The serialized Queue object
	 */
	public function serializeQueue(Queue $queue);

	/**
	 * Unserialize the Queue object
	 *
	 * @param  string $queue
	 *
	 * @return Queue
	 */
	public function unserializeQueue($queue);

}