<?php

use Laminas\ServiceManager\ServiceManager;

$dependencyMap  = require_once('dependencies.php');
$serviceManager = new ServiceManager($dependencyMap);
