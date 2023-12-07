<?php

declare(strict_types=1);

use Quantum\Quantum;

// Uncomment if you want to use the session

/*
session_start();
*/

require  __DIR__ . '/../app/bootstrap.php';

$app = new Quantum();

// Set the services
require  __DIR__ . '/../app/config/services.php';

// Register the events
require __DIR__ . '/../app/config/events.php';

// Set the controller routes
require  __DIR__ . '/../app/config/routes.php';

$app->run();