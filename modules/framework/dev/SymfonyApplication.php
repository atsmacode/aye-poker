<?php

require './vendor/autoload.php';
require './config/container.php';

use Atsmacode\Framework\Console\Commands\ExampleMigrator;
use Atsmacode\Framework\Database\DbalTestFactory;
use Atsmacode\Framework\Pdo\PdoTestFactory;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new ExampleMigrator(
    null,
    $serviceManager,
    new DbalTestFactory(),
    new PdoTestFactory()
));
$application->run();
