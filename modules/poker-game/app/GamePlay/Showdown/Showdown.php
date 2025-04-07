<?php

namespace Atsmacode\PokerGame\GamePlay\Showdown;

use Atsmacode\PokerGame\GamePlay\HandIdentifier\HandIdentifier;
use Atsmacode\PokerGame\State\Game\GameState;

/**
 * Decide the winnder by comparing the hand types/ranks found by HandIdentifier.
 */
class Showdown
{
    public HandIdentifier $handIdentifier;
    public GameState $gameState;

    /**
     * @var array
     */
    private $communityCards = [];

    /**
     * @var array<mixed>
     */
    private array $playerHands = [];

    public function __construct(GameState $gameState)
    {
        $this->handIdentifier = new HandIdentifier();
        $this->gameState = $gameState;
        $this->communityCards = $this->gameState->getCommunityCards();
    }

    public function decideWinner(): array
    {
        /*
         * foreach handType, if there are more than 1 players with that hand type,
         * retain only the one with the highest kicker & active cards as appropriate
         * then compare the hand rankings of each remaining player hand.
         */
        foreach ($this->handIdentifier->getHandTypes() as $handType) {
            $playerHandsOfType = $this->getPlayerhandsOfType($handType['id']);

            if (count($playerHandsOfType) > 1) {
                $this->identifyHighestRankedHandAndKickerOfThisType(
                    $this->playerHands,
                    $playerHandsOfType,
                    $handType
                );
            }
        }

        return $this->highestRankedPlayerHand();
    }

    protected function identifyHighestRankedHandAndKickerOfThisType(
        array $playerHands,
        array $playerHandsOfType,
        array $handType,
    ): void {
        /*
         * Remove hands of this type from the array. That way we can only
         * retain the highest rank/kicker-ed hand and put it back in to be
         * compared against the other highest ranked/kicker-ed hand types.
         */
        $this->playerHands = array_filter($playerHands, function ($value) use ($handType) {
            return $value['handType']['id'] !== $handType['id'];
        });

        $handsOfThisTypeRanked = $this->getBestHandByHighestActiveCardRank(
            $playerHandsOfType
        );

        if (count($handsOfThisTypeRanked) > 1) {
            /*
             * TODO: split pots, this functionality is currently
             * set to only return the first one even if multiple players
             * share the same best active cards and kickers.
             */
            $handsOfThisTypeRanked = $this->getBestHandByHighestKicker(
                $playerHandsOfType
            );
        }

        $highestRankedHandOfType = $handsOfThisTypeRanked[array_key_first($handsOfThisTypeRanked)];

        array_push($this->playerHands, $highestRankedHandOfType);
    }

    public function compileHands(): self
    {
        foreach ($this->getContinuingPlayerSeats($this->gameState->getPlayers()) as $player) {
            $compileInfo = (new HandIdentifier())->identify(
                $this->gameState->getWholeCards()[$player['player_id']],
                $this->communityCards
            )->getIdentifiedHandType();

            $compileInfo['highestActiveCard'] = max($compileInfo['activeCards']);
            $compileInfo['player'] = $player;

            $this->playerHands[] = $compileInfo;
        }

        return $this;
    }

    private function getContinuingPlayerSeats(array $players): array
    {
        return array_filter($players, function ($player) {
            return 1 === $player['active'] && 1 === $player['can_continue'];
        });
    }

    private function getPlayerhandsOfType(int $handTypeId): array
    {
        return array_filter($this->playerHands, function ($value) use ($handTypeId) {
            return $value['handType']['id'] == $handTypeId;
        });
    }

    private function getBestHandByHighestActiveCardRank(array $playerHandsOfType): array
    {
        $maxActiveCard = max(array_column($playerHandsOfType, 'highestActiveCard'));

        return array_filter($playerHandsOfType, function ($value) use ($maxActiveCard) {
            return $value['highestActiveCard'] == $maxActiveCard;
        });
    }

    private function getBestHandByHighestKicker(array $playerHandsOfType): array
    {
        $maxKicker = max(array_column($playerHandsOfType, 'kicker'));

        return array_filter($playerHandsOfType, function ($value) use ($maxKicker) {
            return $value['kicker'] == $maxKicker;
        });
    }

    private function highestRankedPlayerHand(): array
    {
        uasort($this->playerHands, function ($a, $b) {
            if ($a['handType']['ranking'] == $b['handType']['ranking']) {
                return 0;
            }

            return ($a['handType']['ranking'] > $b['handType']['ranking']) ? 1 : -1;
        });

        return $this->playerHands[array_key_first($this->playerHands)];
    }
}
