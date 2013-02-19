<?php namespace TaskQueue\Repositories;

use TaskQueue\Entities\Queue;

interface QueueRepository {

	public function getAll();

	public function save(Queue $queue);

	public function delete(Queue $queue);

	public function serializeQueue(Queue $queue);

	public function unserializeQueue($queue);

}