<?php

require './vendor/autoload.php';
require 'modules/framework/config/container.php';

use Atsmacode\Framework\Console\Commands\ExampleMigrator;
use Atsmacode\Framework\Database\DbalTestFactory;
use Atsmacode\Framework\Pdo\PdoTestFactory;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new ExampleMigrator(
    'app:create-database',
    $serviceManager,
    new DbalTestFactory(),
    new PdoTestFactory()
));
$application->run();
