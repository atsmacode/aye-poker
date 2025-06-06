<?php

namespace Atsmacode\PokerGame;

use Laminas\ServiceManager\AbstractFactory\ConfigAbstractFactory;

class DependencyConfig
{
    public function get(): array
    {
        return [
            // ConfigAbstractFactory::class => [
            //     \Atsmacode\PokerGame\Models\Hand::class => [
            //         \Atsmacode\Framework\Database\ConnectionInterface::class,
            //         Psr\Log\LoggerInterface::class
            //     ],
            // ],
            'dependencies' => [
                // 'abstract_factories' => [
                //     ConfigAbstractFactory::class,
                // ],
                'invokables' => [
                    GamePlay\GameStyle\PotLimitHoldEm::class,
                    GamePlay\GameStyle\PotLimitOmaha::class,
                    Controllers\Player\Controller::class,
                ],
                'factories' => [
                    PokerGameConfigProvider::class => PokerGameConfigProviderFactory::class,
                    \Atsmacode\Framework\Database\ConnectionInterface::class => Database\DbalLiveFactory::class,
                    \PDO::class => Database\PdoLiveFactory::class,
                    \Psr\Log\LoggerInterface::class => LoggerFactory::class,
                    GamePlay\Dealer\PokerDealer::class => GamePlay\Dealer\PokerDealerFactory::class,
                    Models\Street::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\Table::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\Hand::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\Player::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\TableSeat::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\HandStreet::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\PlayerAction::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\Stack::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\Pot::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\PlayerActionLog::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\WholeCard::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\HandStreetCard::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\HandType::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\Deck::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Models\Game::class => \Atsmacode\Framework\Models\ModelFactory::class,
                    Repository\Hand\HandRepository::class => \Atsmacode\Framework\Database\DatabaseFactory::class,
                    Repository\Table\TableRepository::class => \Atsmacode\Framework\Database\DatabaseFactory::class,
                    Repository\TableSeat\TableSeatRepository::class => \Atsmacode\Framework\Database\DatabaseFactory::class,
                    Repository\HandStreetCard\HandStreetCardRepository::class => \Atsmacode\Framework\Database\DatabaseFactory::class,
                    Repository\WholeCard\WholeCardRepository::class => \Atsmacode\Framework\Database\DatabaseFactory::class,
                    Repository\Stack\StackRepository::class => \Atsmacode\Framework\Database\DatabaseFactory::class,
                    Repository\PlayerAction\PlayerActionRepository::class => \Atsmacode\Framework\Database\DatabaseFactory::class,
                    State\Player\PlayerState::class => State\Player\PlayerStateFactory::class,
                    Handlers\Bet\BetHandler::class => Handlers\Bet\BetHandlerFactory::class,
                    Services\Pots\PotService::class => Services\Pots\PotServiceFactory::class,
                    Services\Blinds\BlindService::class => Services\Blinds\BlindServiceFactory::class,
                    GamePlay\HandStep\NewStreet::class => GamePlay\HandStep\NewStreetFactory::class,
                    GamePlay\HandStep\Start::class => GamePlay\HandStep\StartFactory::class,
                    GamePlay\HandStep\Showdown::class => GamePlay\HandStep\ShowdownFactory::class,
                    Factory\PlayerActionFactory::class => Factory\PlayerActionFactoryFactory::class,
                    Repository\GameState\GameStateRepository::class => Repository\GameState\GameStateRepositoryFactory::class,
                    Repository\Game\GameRepository::class => \Atsmacode\Framework\Database\DatabaseFactory::class,
                    GamePlay\GamePlay::class => GamePlay\GamePlayFactory::class,
                    State\Game\GameState::class => State\Game\GameStateFactory::class,
                    Handlers\Action\ActionHandler::class => Handlers\Action\ActionHandlerFactory::class,
                    Controllers\PotLimitHoldEm\SitController::class => Controllers\SitControllerFactory::class,
                    Controllers\PotLimitOmaha\SitController::class => Controllers\SitControllerFactory::class,
                    Controllers\PotLimitHoldEm\PlayerActionController::class => Controllers\PlayerActionControllerFactory::class,
                    Controllers\PotLimitOmaha\PlayerActionController::class => Controllers\PlayerActionControllerFactory::class,
                    Controllers\Player\Controller::class => Controllers\ControllerFactory::class,
                    Handlers\Sit\SitHandler::class => Handlers\Sit\SitHandlerFactory::class,
                    Services\Sit\SitService::class => Services\Sit\SitServiceFactory::class,
                    Services\GamePlay\GamePlayService::class => Services\GamePlay\GamePlayServiceFactory::class,
                    Services\Games\GameService::class => Services\Games\GameServiceFactory::class,
                ],
            ],
        ];
    }
}
