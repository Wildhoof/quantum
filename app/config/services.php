<?php

use Quantum\Database\Database;
use Quantum\Database\Query;

use Quantum\View;

$app->container()->append(View::class, new View(
    __DIR__ . '/../views/'
));

// Uncomment, if you want to use the database in your application

/*
$config = include 'database.php';

$app->container()->append(Database::class, new Database(
    hostname: $config['hostname'],
    database: $config['database'],
    charset: $config['charset'],
    username: $config['username'],
    password: $config['password']
));

$app->container()->append(
    Query::class,
    $app->container()->get(Query::class)
);
*/