<?php

Autoloader::namespaces(array(
	'TaskQueue' => __DIR__ . DS . 'TaskQueue'
));

Autoloader::alias('TaskQueue\\Queues', 'Queues');

/*
|--------------------------------------------------------------------------
| Register Queues to the IoC container
|--------------------------------------------------------------------------
|
| Register Queues to the IoC container for easy access and use. We
| register it as a singleton since there is no need to have multiple
| instances at the same time.
|
*/
IoC::singleton('Queues', function()
{
	$repository = new TaskQueue\Repositories\FluentQueueRepository;

	return new TaskQueue\Queues($repository);
});

/*
|--------------------------------------------------------------------------
| Add the routes
|--------------------------------------------------------------------------
|
| Adds the routes that handle the queues. Easily accessible so it can be
| run asynchronous and so it doesn't depend on being accessed by artisan.
|
*/
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