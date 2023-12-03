<?php

declare(strict_types=1);

use Quantum\Quantum;

require  __DIR__ . '/../app/bootstrap.php';

$app = new Quantum();

// Set the services
require  __DIR__ . '/../app/config/services.php';

// Set the controller routes
require  __DIR__ . '/../app/config/routes.php';

$app->run();