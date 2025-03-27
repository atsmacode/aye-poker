<?php

namespace Atsmacode\FRamework\Tests;

use Atsmacode\Framework\Database\ConnectionInterface;
use Atsmacode\Framework\Database\DbalTestFactory;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    protected ServiceManager $container;

    protected function setUp(): void
    {
        parent::setUp();

        $dependencyMap   = require('modules/framework/config/dependencies.php');
        $this->container = new ServiceManager($dependencyMap);

        $this->container->setFactory(ConnectionInterface::class, new DbalTestFactory());
    }
}
