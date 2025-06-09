<?php

namespace Atsmacode\PokerGame\Tests;

use Atsmacode\Framework\Database\ConnectionInterface;
use Atsmacode\PokerGame\Database\DbalTestFactory;
use Atsmacode\PokerGame\Factory\PlayerActionFactory;
use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\Handlers\Action\ActionHandler;
use Atsmacode\PokerGame\Models\Game;
use Atsmacode\PokerGame\Models\Hand;
use Atsmacode\PokerGame\Models\HandStreet;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Models\Street;
use Atsmacode\PokerGame\Models\Table;
use Atsmacode\PokerGame\Models\TableSeat;
use Atsmacode\PokerGame\Models\WholeCard;
use Atsmacode\PokerGame\PokerGameConfigProvider;
use Atsmacode\PokerGame\Repository\HandStreetCard\HandStreetCardRepository;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\Repository\WholeCard\WholeCardRepository;
use Atsmacode\PokerGame\Services\GamePlay\GamePlayService;
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
    protected GamePlayService $gamePlayService;
    protected Fake $fake;
    protected WholeCardRepository $wholeCardRepo;
    protected HandStreetCardRepository $handStreetCardRepo;
    protected TableSeatRepository $tableSeatRepo;
    protected Game $games;

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
        $this->gamePlayService = $this->container->build(GamePlayService::class);
        $this->fake = Faker\Factory::create();
        $this->wholeCardRepo = $this->container->build(WholeCardRepository::class);
        $this->handStreetCardRepo = $this->container->build(HandStreetCardRepository::class);
        $this->tableSeatRepo = $this->container->build(TableSeatRepository::class);
        $this->games = $this->container->build(Game::class);

        //$this->container->get(ConnectionInterface::class)->beginTransaction();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        //$this->container->get(ConnectionInterface::class)->rollback();
    }
}
