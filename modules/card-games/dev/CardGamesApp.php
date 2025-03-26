<?php

require './vendor/autoload.php';
require './config/container.php';

use Atsmacode\CardGames\Console\Commands\BuildCardGames;
use Atsmacode\CardGames\DbalTestFactory;
use Atsmacode\CardGames\PdoTestFactory;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new BuildCardGames(
    null,
    $serviceManager,
    new DbalTestFactory(),
    new PdoTestFactory()
));
$application->run();