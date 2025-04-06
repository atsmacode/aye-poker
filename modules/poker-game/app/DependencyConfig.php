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
                    State\PlayerState\PlayerState::class,
                    GamePlay\Game\PotLimitHoldEm::class,
                    GamePlay\Game\PotLimitOmaha::class,
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
                    State\PlayerState\PlayerState::class => State\PlayerState\PlayerStateFactory::class,
                    Handlers\BetHandler\BetHandler::class => Handlers\BetHandler\BetHandlerFactory::class,
                    Services\PotService\PotService::class => Services\PotService\PotServiceFactory::class,
                    GamePlay\HandStep\NewStreet::class => GamePlay\HandStep\NewStreetFactory::class,
                    GamePlay\HandStep\Start::class => GamePlay\HandStep\StartFactory::class,
                    GamePlay\HandStep\Showdown::class => GamePlay\HandStep\ShowdownFactory::class,
                    Factory\PlayerActionFactory::class => Factory\PlayerActionFactoryFactory::class,
                    GameData\GameData::class => GameData\GameDataFactory::class,
                    GamePlay\GamePlay::class => GamePlay\GamePlayFactory::class,
                    State\GameState\GameState::class => State\GameState\GameStateFactory::class,
                    Handlers\ActionHandler\ActionHandler::class => Handlers\ActionHandler\ActionHandlerFactory::class,
                    Controllers\PotLimitHoldEm\SitController::class => Controllers\SitControllerFactory::class,
                    Controllers\PotLimitOmaha\SitController::class => Controllers\SitControllerFactory::class,
                    Controllers\PotLimitHoldEm\PlayerActionController::class => Controllers\PlayerActionControllerFactory::class,
                    Controllers\PotLimitOmaha\PlayerActionController::class => Controllers\PlayerActionControllerFactory::class,
                    Controllers\Player\Controller::class => Controllers\ControllerFactory::class,
                    Handlers\SitHandler\SitHandler::class => Handlers\SitHandler\SitHandlerFactory::class,
                    Services\JoinTable::class => Services\JoinTableFactory::class,
                    Services\GamePlayService::class => Services\GamePlayServiceFactory::class,
                ],
            ],
        ];
    }
}
