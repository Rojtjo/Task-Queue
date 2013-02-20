# Task Queue #

Easily create queued tasks.

```php
// Get a Queues instance
$queues = IoC::resolve('queues');

// Add a queue
$queues->add('taskname:method', array('first argument', 'second argument'));

// Add a queue that runs immediately
$queues->add('taskname:method', array('first argument', 'second argument'), true);

// Run a queue
$queue = $queues->get(1); // Get queue by id

// Run the queue
$queues->run($queue);

// Or run it asynchronous
$queues->runAsync($queue);
```

More info soon.