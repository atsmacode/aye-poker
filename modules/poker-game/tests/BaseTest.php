<?php

namespace Atsmacode\PokerGame\Tests;

use Atsmacode\Framework\Database\ConnectionInterface;
use Atsmacode\PokerGame\Handlers\ActionHandler\ActionHandler;
use Atsmacode\PokerGame\Database\DbalTestFactory;
use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\Factory\PlayerActionFactory;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Models\WholeCard;
use Atsmacode\PokerGame\PokerGameConfigProvider;
use Atsmacode\PokerGame\Repository\Player\PlayerRepository;
use Atsmacode\PokerGame\Services\GamePlay\GamePlayService;
use Atsmacode\PokerGame\Services\Sit\SitService;
use Faker;
use Faker\Generator as Fake;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    protected ServiceManager $container;
    protected Table $tables;
    protected Hand $hands;
    protected Player $players;
    protected TableSeat $tableSeats;
    protected HandStreet $handStreets;
    protected PlayerActionFactory $playerActionFactory;
    protected WholeCard $wholeCards;
    protected Street $streets;
    protected PokerDealer $pokerDealer;
    protected ActionHandler $actionHandler;
    protected SitService $sitService;
    protected GamePlayService $gamePlayService;
    protected Fake $fake;
    protected PlayerRepository $playerRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $config = (new PokerGameConfigProvider())->get();
        $pokerGameDependencyMap = $config['dependencies'];

        $this->container = new ServiceManager($pokerGameDependencyMap);
        $this->container->setFactory(ConnectionInterface::class, new DbalTestFactory());

        $this->tables = $this->container->build(Table::class);
        $this->hands = $this->container->build(Hand::class);
        $this->players = $this->container->build(Player::class);
        $this->tableSeats = $this->container->build(TableSeat::class);
        $this->handStreets = $this->container->build(HandStreet::class);
        $this->playerActionFactory = $this->container->build(PlayerActionFactory::class);
        $this->wholeCards = $this->container->build(WholeCard::class);
        $this->streets = $this->container->build(Street::class);
        $this->pokerDealer = $this->container->build(PokerDealer::class);
        $this->actionHandler = $this->container->build(ActionHandler::class);
        $this->sitService = $this->container->build(SitService::class);
        $this->gamePlayService = $this->container->build(GamePlayService::class);
        $this->fake = Faker\Factory::create();
        $this->playerRepo = $this->container->build(PlayerRepository::class);

        $this->container->get(ConnectionInterface::class)->beginTransaction();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->container->get(ConnectionInterface::class)->rollback();
    }
}
