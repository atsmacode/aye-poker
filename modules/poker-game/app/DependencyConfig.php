<?php

namespace Atsmacode\PokerGame;

use Atsmacode\Framework\Database\DatabaseFactory;
use Atsmacode\Framework\Models\ModelFactory;
use Atsmacode\PokerGame\Factory\PlayerActionFactory;
use Atsmacode\PokerGame\Factory\PlayerActionFactoryFactory;
use Atsmacode\PokerGame\GamePlay\Dealer\PokerDealer;
use Atsmacode\PokerGame\GamePlay\HandFlow\NewStreet;
use Atsmacode\PokerGame\GamePlay\HandFlow\Showdown;
use Atsmacode\PokerGame\GamePlay\HandFlow\Start;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\CreatePlayerActions;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\DealCards;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\SetDealerAndBlinds;
use Atsmacode\PokerGame\GamePlay\HandFlow\StartSteps\LoadStacks;
use Atsmacode\PokerGame\Handlers\Action\ActionHandler;
use Atsmacode\PokerGame\Handlers\Bet\BetHandler;
use Atsmacode\PokerGame\Handlers\Sit\SitHandler;
use Atsmacode\PokerGame\Models\Player;
use Atsmacode\PokerGame\Pipelines\GameStatePipeline;
use Atsmacode\PokerGame\Repository\Game\GameRepository;
use Atsmacode\PokerGame\Repository\GameState\GameStateRepository;
use Atsmacode\PokerGame\Repository\Hand\HandRepository;
use Atsmacode\PokerGame\Repository\HandStreetCard\HandStreetCardRepository;
use Atsmacode\PokerGame\Repository\PlayerAction\PlayerActionRepository;
use Atsmacode\PokerGame\Repository\Stack\StackRepository;
use Atsmacode\PokerGame\Repository\Table\TableRepository;
use Atsmacode\PokerGame\Repository\TableSeat\TableSeatRepository;
use Atsmacode\PokerGame\Repository\WholeCard\WholeCardRepository;
use Atsmacode\PokerGame\Services\Blinds\BlindService;
use Atsmacode\PokerGame\Services\GamePlay\GamePlayService;
use Atsmacode\PokerGame\Services\Games\GameService;
use Atsmacode\PokerGame\Services\Pots\PotService;
use Atsmacode\PokerGame\State\Game\GameState;
use Atsmacode\PokerGame\State\Game\GameStateFactory;
use Atsmacode\PokerGame\State\Player\PlayerState;
use Psr\Container\ContainerInterface;

function factory(callable $factory): callable {
    return function (ContainerInterface $container) use ($factory) {
        return $factory($container);
    };
}

class DependencyConfig
{
    public function get(): array
    {
        return [
            'dependencies' => [
                'invokables' => [
                    GamePlay\GameStyle\PotLimitHoldEm::class,
                    GamePlay\GameStyle\PotLimitOmaha::class,
                    Controllers\Player\Controller::class,
                    DealCards::class,
                ],
                'factories' => [
                    PokerGameConfigProvider::class => factory(fn() => new PokerGameConfigProvider('')),
                    \Atsmacode\Framework\Database\ConnectionInterface::class => Database\DbalLiveFactory::class,
                    \PDO::class => Database\PdoLiveFactory::class,
                    \Psr\Log\LoggerInterface::class => LoggerFactory::class,
                    PlayerActionFactory::class => PlayerActionFactoryFactory::class,

                    // Models
                    Models\Street::class => ModelFactory::class,
                    Models\Table::class => ModelFactory::class,
                    Models\Hand::class => ModelFactory::class,
                    Models\Player::class => ModelFactory::class,
                    Models\TableSeat::class => ModelFactory::class,
                    Models\HandStreet::class => ModelFactory::class,
                    Models\PlayerAction::class => ModelFactory::class,
                    Models\Stack::class => ModelFactory::class,
                    Models\Pot::class => ModelFactory::class,
                    Models\PlayerActionLog::class => ModelFactory::class,
                    Models\WholeCard::class => ModelFactory::class,
                    Models\HandStreetCard::class => ModelFactory::class,
                    Models\HandType::class => ModelFactory::class,
                    Models\Deck::class => ModelFactory::class,
                    Models\Game::class => ModelFactory::class,

                    // Repos
                    HandRepository::class => DatabaseFactory::class,
                    TableRepository::class => DatabaseFactory::class,
                    TableSeatRepository::class => DatabaseFactory::class,
                    HandStreetCardRepository::class => DatabaseFactory::class,
                    WholeCardRepository::class => DatabaseFactory::class,
                    StackRepository::class => DatabaseFactory::class,
                    GameRepository::class => DatabaseFactory::class,
                    PlayerActionRepository::class => DatabaseFactory::class,
                    GameStateRepository::class => factory(fn($c) => new GameStateRepository(
                        $c->build(Models\Hand::class),
                        $c->get(TableSeatRepository::class),
                        $c->get(WholeCardRepository::class),
                        $c->get(PlayerActionRepository::class),
                        $c->get(GameRepository::class)
                    )),

                    // Controllers
                    Controllers\Player\Controller::class => Controllers\ControllerFactory::class,
                    Controllers\PotLimitHoldEm\SitController::class => Controllers\SitControllerFactory::class,
                    Controllers\PotLimitOmaha\SitController::class => Controllers\SitControllerFactory::class,
                    Controllers\PotLimitHoldEm\PlayerActionController::class => Controllers\PlayerActionControllerFactory::class,
                    Controllers\PotLimitOmaha\PlayerActionController::class => Controllers\PlayerActionControllerFactory::class,

                    // GamePlay
                    GamePlay\HandFlow\HandFlow::class => GamePlay\HandFlow\HandFlowFactory::class, // Has options for GameState
                    Start::class => factory(fn($c) => new Start($c->build(GameStatePipeline::class))),
                    NewStreet::class => factory(fn($c) => new NewStreet(
                        $c->build(Models\Street::class),
                        $c->build(Models\TableSeat::class),
                        $c->build(Models\HandStreet::class),
                        $c->build(Models\PlayerAction::class)
                    )),
                    Showdown::class => factory(fn($c) => new Showdown($c->build(PotService::class))),
                    PokerDealer::class => factory(fn($c) => new PokerDealer(
                        $c->build(Models\WholeCard::class),
                        $c->build(Models\HandStreetCard::class),
                        $c->build(Models\Deck::class)
                    )),

                    // State
                    GameState::class => GameStateFactory::class, // Has options for Hand model
                    PlayerState::class => factory(fn($c) => new PlayerState(
                        $c->get(TableSeatRepository::class),
                        $c->get(Player::class)
                    )),

                    // Handlers
                    Handlers\Action\ActionHandler::class => Handlers\Action\ActionHandlerFactory::class, // Has options for GameState
                    BetHandler::class => factory(fn($c) => new BetHandler(
                        $c->get(PotService::class),
                        $c->get(StackRepository::class)
                    )),
                    SitHandler::class => factory(fn($c) => new SitHandler(
                        $c->get(GameState::class),
                        $c->get(Models\Hand::class),
                        $c->get(TableSeatRepository::class),
                        $c->get(PlayerState::class)
                    )),

                    // Services
                    GamePlayService::class => factory(fn($c) => new GamePlayService(
                        $c,
                        $c->get(ActionHandler::class),
                        $c->get(SitHandler::class),
                        $c->get(HandRepository::class)
                    )),
                    GameService::class => factory(fn($c) => new GameService(
                        $c->build(Models\Table::class),
                        $c->build(Models\TableSeat::class),
                        $c->build(Models\Player::class),
                        $c->build(Models\Game::class),
                    )),
                    PotService::class => factory(fn($c) => new PotService(
                        $c->get(StackRepository::class),
                        $c->build(Models\Pot::class)
                    )),
                    BlindService::class => factory(fn($c) => new BlindService(
                        $c->get(BetHandler::class),
                        $c->get(PotService::class),
                        $c->build(Models\PlayerActionLog::class),
                        $c->build(Models\TableSeat::class)
                    )),

                    // Pipelines
                    CreatePlayerActions::class => factory(fn($c) => new CreatePlayerActions(
                        $c->build(Models\Street::class),
                        $c->build(Models\HandStreet::class),
                        $c->build(Models\PlayerAction::class),
                    )),
                    SetDealerAndBlinds::class => factory(fn($c) => new SetDealerAndBlinds(
                        $c->build(Models\Street::class),
                        $c->build(Models\HandStreet::class),
                        $c->build(Models\PlayerAction::class),
                        $c->build(Models\TableSeat::class),
                        $c->get(BlindService::class)
                    )),
                    LoadStacks::class => factory(fn($c) => new LoadStacks(
                        $c->build(Models\Stack::class),
                    )),
                    GameStatePipeline::class => factory(fn($c) => new GameStatePipeline($c)),

                    // ...
                ],
            ],
        ];
    }
}
