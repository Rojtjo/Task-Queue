<?php

Autoloader::namespaces(array(
	'TaskQueue' => __DIR__ . DS . 'TaskQueue'
));

Autoloader::alias('TaskQueue\\Queues', 'Queues');

IoC::singleton('Queues', function()
{
	$repository = new TaskQueue\Repositories\FluentQueueRepository;

	return new TaskQueue\Queues($repository);
});

Route::get(Bundle::get('task-queue.handles'), function()
{
	$queues = IoC::resolve('Queues');

	$queues->runAll(true);
});

Route::get(Bundle::get('task-queue.handles') . '/(:num)', function($id)
{
	$queues = IoC::resolve('Queues');

	$queue = $queues->get($id);

	if( ! is_null($queue))
	{
		$queues->run($queue);
		Log::write('queues', 'run ' . $queue->id);
	}
});