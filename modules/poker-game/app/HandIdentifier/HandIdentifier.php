<?php

namespace Atsmacode\PokerGame\HandIdentifier;

use Atsmacode\PokerGame\Constants\HandType;
use Atsmacode\CardGames\Constants\Rank;
use Atsmacode\CardGames\Constants\Suit;

class HandIdentifier
{
    private array $handTypes;

    private array $identifiedHandType = [
        'handType'    => null,
        'activeCards' => [],
        'kicker'      => null
    ];

    private array      $allCards;
    private int|bool   $highCard = false;
    private array      $pairs = [];
    private array      $threeOfAKind = [];
    private array      $straight;
    private array      $flush;
    private array|bool $fullHouse = false;
    private array      $fourOfAKind;
    private array      $straightFlush;
    private array      $royalFlush ;

    public function __construct()
    {
        $this->handTypes = HandType::ALL;
    }

    public function getHandTypes(): array
    {
        return $this->handTypes;
    }

    public function getHighCard(): int|bool
    {
        return $this->highCard;
    }

    public function getPairs(): array
    {
        return $this->pairs;
    }

    public function getThreeOfAKind(): array
    {
        return $this->threeOfAKind;
    }

    public function getFourOfAKind(): array
    {
        return $this->fourOfAKind;
    }

    public function getIdentifiedHandType(): array
    {
        return $this->identifiedHandType;
    }

    public function identify(array $wholeCards, array $communityCards): self
    {
        $this->allCards = array_merge($wholeCards, $communityCards);

        if (true === $this->hasRoyalFlush()) { return $this; }

        if (true === $this->hasStraightFlush()) { return $this; }

        if (true === $this->hasFourOfAKind()) { return $this; }

        if (true === $this->hasFullHouse()) { return $this; }

        if (true === $this->hasFlush()) { return $this; }

        if (true === $this->hasStraight()) { return $this; }

        if (true === $this->hasThreeOfAKind()) { return $this; }

        if (true === $this->hasTwoPair()) { return $this; }

        if (true === $this->hasPair()) { return $this; }

        return $this->highestCard();
    }

    private function checkFlushForAceKicker(array $activeCards): mixed
    {
        if (in_array(1, $activeCards)) {
            $activeCardsLessThanAce = array_filter($activeCards, function($value) {
                return Rank::ACE_RANK_ID !== $value;
            });

            return max($activeCardsLessThanAce);
        }

        return false;
    }

    private function checkForHighAceActiveCardRanking(array $rank): int|bool
    {
        if ($rank['ranking'] === Rank::ACE_RANK_ID) { return Rank::ACE_HIGH_RANK_ID; }

        return false;
    }

    /**
     * @param array|object $haystack
     * @param string $columm
     */
    private function getMax($haystack, string $columm): mixed
    {
        return max(array_column($haystack, $columm));
    }

    /**
     * @param array|object $haystack
     * @param string $columm
     */
    private function getMin($haystack, $columm): mixed
    {
        return min(array_column($haystack, $columm));
    }

    private function getKicker(array $activeCards = null): ?int
    {
        $cardRankings = array_column($this->sortAllCardsByDescRanking(), 'ranking');

        /**
         * Check against $this->highCard & activeCards so only
         * inactive cards are used as kickers.
         * 
         * @todo This won't yet cover all cases as it will return
         * null if none of the player's inactive cards meet the
         * two conditions.
         */
        foreach ($cardRankings as $cardRanking) {
            if (
                ($this->highCard && $cardRanking != $this->highCard) ||
                ($activeCards && !in_array($cardRanking, $activeCards))
            ) {
                return $cardRanking;
            }
        }
    }

    private function getHandType(string $name): array|false
    {
        $key = array_search($name, array_column($this->handTypes, 'name'));

        if (array_key_exists($key, $this->handTypes)) { return $this->handTypes[$key]; }

        return false;
    }

    private function filterAllCards(string $column, $filter): array
    {
        return array_filter($this->allCards, function ($value) use ($column, $filter) {
            return $value[$column] === $filter;
        });
    }

    private function sortAllCardsByDescRanking(): array
    {
        usort($this->allCards, function ($a, $b) {
            if ($a['ranking'] == $b['ranking']) { return 0; }

            return $a['ranking'] > $b['ranking'] ? -1 : 1;
        });

        return array_values($this->allCards);
    }

    private function removeDuplicates(array $cards): array
    {
        return array_values(array_filter($cards, function ($value, $key) use ($cards) {
            if (array_key_exists($key - 1, $cards)) {
                return $value['ranking'] !== $cards[$key - 1]['ranking'];
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH));
    }

    private function filterStraightCards(array $cards): array
    {
        return array_filter($cards, function($value, $key) use ($cards) {
            $nextCardExists     = array_key_exists($key + 1, $cards);
            $previousCardExists = array_key_exists($key - 1, $cards);

            $nextCardRankingPlusOne      = $nextCardExists ? $cards[$key + 1]['ranking'] + 1 : null;
            $previousCardRankingMinusOne = $previousCardExists? $cards[$key - 1]['ranking'] - 1 : null;

            if ($nextCardExists && !$previousCardExists) { return $value['ranking'] === $nextCardRankingPlusOne; }

            if (!$nextCardExists && $previousCardExists) { return $value['ranking'] === $previousCardRankingMinusOne; }

            return $value['ranking'] === $previousCardRankingMinusOne && $value['ranking'] === $nextCardRankingPlusOne;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function highestCard(): self
    {
        if ($this->getMin($this->allCards, 'ranking') === 1) {
            $this->highCard = Rank::ACE_HIGH_RANK_ID;
        } else {
            $this->highCard = $this->getMax($this->allCards, 'ranking');
        }

        $this->identifiedHandType['handType']      = $this->getHandType('High Card');
        $this->identifiedHandType['activeCards'][] = $this->highCard;
        $this->identifiedHandType['kicker']        = $this->getKicker();

        return $this;
    }

    public function hasPair(): bool|self
    {
        foreach (Rank::ALL as $rank) {
            if (count($this->filterAllCards('rank_id', $rank['rank_id'])) === 2) {
                $this->pairs[] = $rank;
                $this->identifiedHandType['activeCards'][] = $this->checkForHighAceActiveCardRanking($rank) ?: $rank['ranking'];
                /*
                 * The showdown may be called pre-flop when the pot is checked down to BB.
                 * In which case they may have a pair and no other kicker rank.
                 */
                if (count($this->allCards) > 2) {
                    $this->identifiedHandType['kicker'] = $this->getKicker($this->identifiedHandType['activeCards']);
                } else {
                    $this->identifiedHandType['kicker'] = $rank['ranking'];
                }
            }
        }

        if (count($this->pairs) === 1) {
            $this->identifiedHandType['handType'] = $this->getHandType('Pair');
            return true;
        }

        return $this;
    }

    public function hasTwoPair(): bool|self
    {
        foreach(Rank::ALL as $rank){
            if (2 === count($this->filterAllCards('rank_id', $rank['rank_id']))) {
                $this->pairs[]                             = $rank;
                $this->identifiedHandType['activeCards'][] = $this->checkForHighAceActiveCardRanking($rank) ?: $rank['ranking'];
                /*
                 * The showdown may be called pre-flop when the pot is checked down to BB.
                 * In which case they may have a pair and no other kicker rank.
                 */
                if (count($this->allCards) > 2) {
                    $this->identifiedHandType['kicker'] = $this->getKicker($this->identifiedHandType['activeCards']);
                } else {
                    $this->identifiedHandType['kicker'] = $rank['ranking'];
                }
            }
        }

        if (2 <= count($this->pairs)) {
            $this->identifiedHandType['handType'] = $this->getHandType('Two Pair');
            return true;
        }

        $this->pairs = [];

        return $this;
    }

    public function hasThreeOfAKind(): bool|self
    {
        foreach(Rank::ALL as $rank){
            if (3 === count($this->filterAllCards('rank_id', $rank['rank_id']))) {
                $this->threeOfAKind                        = $rank;
                $this->identifiedHandType['handType']      = $this->getHandType('Three of a Kind');
                $this->identifiedHandType['activeCards'][] = $this->checkForHighAceActiveCardRanking($rank) ?: $rank['ranking'];
                $this->identifiedHandType['kicker']        = $this->getKicker($this->identifiedHandType['activeCards']);

                return true;
            }
        }

        /** @todo There could be 2 trips - add handling for this */
        return $this;
    }

    public function hasStraight(): bool|self
    {
        if (true === $this->hasFiveHighStraight()) { return true; }

        if (true === $this->hasAceHighStraight()) { return true; }

        if (true === $this->hasAnyOtherStraight()) { return true; }

        return $this;
    }

    private function hasFiveHighStraight(): bool
    {
        $sortedCardsDesc = array_filter($this->sortAllCardsByDescRanking(), function ($value, $key) {
            $previousCardRanking = null;

            /* Remove duplicates. */
            if (array_key_exists($key - 1, $this->allCards)) {
                $previousCardRanking = $this->allCards[$key - 1]['ranking'];
            }

            switch ($value['ranking']) {
                case Rank::ACE_RANK_ID:
                case Rank::DEUCE_RANK_ID:
                case Rank::THREE_RANK_ID:
                case Rank::FOUR_RANK_ID:
                case Rank::FIVE_RANK_ID:
                    if ($value['ranking'] !== $previousCardRanking) { return true; }
                    break;
            }
        }, ARRAY_FILTER_USE_BOTH);

        $straight = array_slice($sortedCardsDesc, 0, 5);

        if ($straight && 5 === count($straight)) {
            $this->straight                          = $straight;
            $this->identifiedHandType['handType']    = $this->getHandType('Straight');
            $this->identifiedHandType['activeCards'] = array_column($straight, 'ranking');
            $this->identifiedHandType['kicker']      = array_shift($straight)['ranking'];

            return true;
        }

        return false;
    }

    private function hasAceHighStraight(): bool
    {
        $sortedCardsDesc = array_filter($this->sortAllCardsByDescRanking(), function ($value, $key) {
            $previousCardRanking = null;

            /* Remove duplicates. */
            if (array_key_exists($key - 1, $this->allCards)) {
                $previousCardRanking = $this->allCards[$key - 1]['ranking'];
            }

            switch ($value['ranking']) {
                case Rank::ACE_RANK_ID:
                case Rank::KING_RANK_ID:
                case Rank::QUEEN_RANK_ID:
                case Rank::JACK_RANK_ID:
                case Rank::TEN_RANK_ID:
                    if ($value['ranking'] !== $previousCardRanking) { return true; }
                    break;
            }
        }, ARRAY_FILTER_USE_BOTH);

        $straight = array_slice($sortedCardsDesc, 0, 5);

        if ($straight && 5 === count($straight)) {
            $this->straight                          = $straight;
            $this->identifiedHandType['handType']    = $this->getHandType('Straight');
            $this->identifiedHandType['activeCards'] = array_column($straight, 'ranking');
            $this->identifiedHandType['kicker']      = Rank::ACE_HIGH_RANK_ID;

            return true;
        }

        return false;
    }

    private function hasAnyOtherStraight(): bool
    {
        $cardsSortByDesc  = $this->sortAllCardsByDescRanking();
        $removeDuplicates = $this->removeDuplicates($cardsSortByDesc);
        $removeDuplicates = array_slice($removeDuplicates, 0, 5);

        $straight = $this->filterStraightCards($removeDuplicates);

        if ($straight && 5 === count($straight)) {
            $this->straight                          = $straight;
            $this->identifiedHandType['handType']    = $this->getHandType('Straight');
            $this->identifiedHandType['activeCards'] = array_column($straight, 'ranking');
            $this->identifiedHandType['kicker']      = array_shift($straight)['ranking'];

            return true;
        }

        return false;
    }

    public function hasFlush(): bool|self
    {
        foreach (Suit::ALL as $suit) {
            $flushCards = $this->filterAllCards('suit_id', $suit['suit_id']);

            if (5 <= count($flushCards)) {
                $this->flush                             = $flushCards;
                $this->identifiedHandType['activeCards'] = array_column($flushCards, 'ranking');
                $this->identifiedHandType['handType']    = $this->getHandType('Flush');
                $this->identifiedHandType['kicker']      = $this->checkFlushForAceKicker($this->identifiedHandType['activeCards'])
                    ?: array_shift($this->identifiedHandType['activeCards']);

                return true;
            }
        }
        
        return $this;
    }

    public function hasFullHouse(): bool|self
    {
        $this->checkTripsForFullHouse()->checkPairsForFullHouse();

        if ($this->threeOfAKind && 1 <= count($this->pairs)) {
            $this->fullHouse                      = true;
            $this->identifiedHandType['handType'] = $this->getHandType('Full House');
            $this->identifiedHandType['kicker']   = max($this->pairs);

            return true;
        }

        $this->pairs                             = [];
        $this->threeOfAKind                      = [];
        $this->identifiedHandType['activeCards'] = [];

        return $this;
    }

    private function checkTripsForFullHouse(): self
    {
        foreach (Rank::ALL as $rank) {
            if (3 === count($this->filterAllCards('rank_id', $rank['rank_id']))) {
                $this->threeOfAKind                        = $rank;
                $this->identifiedHandType['activeCards'][] = $this->checkForHighAceActiveCardRanking($rank) ?: $rank['ranking'];
            }
        }

        return $this;
    }

    private function checkPairsForFullHouse(): self
    {
        foreach (Rank::ALL as $rank) {
            if (2 === count($this->filterAllCards('rank_id', $rank['rank_id'])) && $this->threeOfAKind !== $rank) {
                $this->pairs[] = $this->checkForHighAceActiveCardRanking($rank) ?: $rank['ranking'];
            }
        }

        return $this;
    }

    public function hasFourOfAKind(): bool|self
    {
        foreach (Rank::ALL as $rank) {
            if (4 === count($this->filterAllCards('rank_id', $rank['rank_id']))) {
                $this->fourOfAKind                         = $rank;
                $this->identifiedHandType['handType']      = $this->getHandType('Four of a Kind');
                $this->identifiedHandType['activeCards'][] = $this->checkForHighAceActiveCardRanking($rank) ?: $rank['ranking'];
                $this->identifiedHandType['kicker']        = $this->getKicker($this->identifiedHandType['activeCards']);

                return true;
            }
        }

        return $this;
    }

    public function hasStraightFlush(): bool|self
    {
        foreach (Suit::ALL as $suit) {
            /* Remove cards not in this suit. This also takes care of duplicates. */
            $onlyThisSuit = array_values(array_filter($this->sortAllCardsByDescRanking(), function ($item) use ($suit) {
                return $item['suit_id'] === $suit['suit_id'];
            }));

            $straightFlush = $this->filterStraightCards($onlyThisSuit);

            if ($straightFlush && 5 === count($straightFlush)) {
                $this->straightFlush                       = $straightFlush;
                $this->identifiedHandType['handType']      = $this->getHandType('Straight Flush');
                $this->identifiedHandType['activeCards'][] = $straightFlush;
                $this->identifiedHandType['kicker']        = $this->getMax($straightFlush, 'ranking');

                return true;
            }
        }

        return $this;
    }

    public function hasRoyalFlush(): bool|self
    {
        foreach (Suit::ALL as $suit) {
            $royalFlush = array_filter($this->allCards, function($value) use ($suit) {
                return $value['suit_id'] === $suit['suit_id'] && $value['rankAbbreviation'] === 'A' ||
                    $value['suit_id'] === $suit['suit_id'] && $value['rankAbbreviation'] === 'K' ||
                    $value['suit_id'] === $suit['suit_id'] && $value['rankAbbreviation'] === 'Q'||
                    $value['suit_id'] === $suit['suit_id'] && $value['rankAbbreviation'] === 'J'||
                    $value['suit_id'] === $suit['suit_id'] && $value['rankAbbreviation'] === '10';
            });

            if ($royalFlush && 5 === count($royalFlush)) {
                $this->royalFlush                        = $royalFlush;
                $this->identifiedHandType['activeCards'] = array_column($this->royalFlush, 'ranking');
                $this->identifiedHandType['handType']    = $this->getHandType('Royal Flush');

                return true;
            }
        }

        return $this;
    }
}
