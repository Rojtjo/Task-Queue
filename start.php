<?php

Autoloader::namespaces(array(
	'TaskQueue' => __DIR__ . DS . 'TaskQueue'
));

Autoloader::alias('TaskQueue\\Queues', 'Queues');