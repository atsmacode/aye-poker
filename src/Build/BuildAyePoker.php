<?php

namespace App\Build;

use Atsmacode\CardGames\Console\Commands\BuildCardGames;
use Atsmacode\PokerGame\Console\Commands\BuildPokerGame;
use Atsmacode\Framework\Console\Commands\CreateDatabase;
use Atsmacode\PokerGame\Database\DbalTestFactory;
use Atsmacode\PokerGame\Database\PdoTestFactory;
use Atsmacode\PokerGame\PokerGameConfigProvider;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Console\Application;


class BuildAyePoker
{
    private Application $application;

    public function __construct()
    {
        $dbalTest = new DbalTestFactory();
        $pdoTest  = new PdoTestFactory();

        $config                 = (new PokerGameConfigProvider())->get();
        $pokerGameDependencyMap = $config['dependencies'];
        $serviceManager         = new ServiceManager($pokerGameDependencyMap);

        $application = new Application();
        $application->add(new CreateDatabase(null, $serviceManager, $dbalTest, $pdoTest));
        $application->add(new BuildCardGames(null, $serviceManager, $dbalTest, $pdoTest));
        $application->add(new BuildPokerGame(null, $serviceManager, $dbalTest, $pdoTest));

        $this->application = $application;
    }

    public function getApp()
    {
        return $this->application;
    }

    public function runApp()
    {
        $this->application->run();
    }
}
