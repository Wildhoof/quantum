<?php

declare(strict_types=1);

use Quantum\Kernel\Event\EventHandler;

$events = $app->container()->get(EventHandler::class);

/* Register your events here */