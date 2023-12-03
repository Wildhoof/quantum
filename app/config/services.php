<?php

use Quantum\View;

$app->container()->append(View::class, new View(
    __DIR__ . '/../views/'
));