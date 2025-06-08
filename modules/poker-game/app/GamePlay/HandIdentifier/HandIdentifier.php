<?php

namespace Atsmacode\PokerGame\GamePlay\HandIdentifier;

use Atsmacode\CardGames\Constants\Rank;
use Atsmacode\CardGames\Constants\Suit;
use Atsmacode\PokerGame\Constants\HandType;

/**
 * Identify Hand Types based on whole/community cards.
 *
 * TODO: use/return HandType Enum
 */
class HandIdentifier
{
    private array $handTypes;

    private array $identifiedHandType = [
        'handType' => null,
        'activeCards' => [],
        'kicker' => null,
    ];

    private array $allCards;
    private array $allCardsDesc;
    private int|bool $highCard = false;
    private array $pairs = [];
    private array $threeOfAKind = [];
    private array $straight;
    private array $flush;
    private bool $fullHouse = false;
    private array $fourOfAKind;
    private array $straightFlush;
    private array $royalFlush;

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

    public function getStraight(): array
    {
        return $this->straight;
    }

    public function getFlush(): array
    {
        return $this->flush;
    }

    public function getThreeOfAKind(): array
    {
        return $this->threeOfAKind;
    }

    /**
     * TODO Return actual boat cards.
     */
    public function getFullHouse(): bool
    {
        return $this->fullHouse;
    }

    public function getFourOfAKind(): array
    {
        return $this->fourOfAKind;
    }

    public function getStraightFlush(): array
    {
        return $this->straightFlush;
    }

    public function getIdentifiedHandType(): array
    {
        return $this->identifiedHandType;
    }

    public function identify(array $wholeCards, array $communityCards): self
    {
        $this->allCards = array_merge($wholeCards, $communityCards);
        $this->allCardsDesc = $this->sortAllCardsByDescRanking();

        if ($this->hasRoyalFlush()) {
            return $this;
        }

        if ($this->hasStraightFlush()) {
            return $this;
        }

        if ($this->hasFourOfAKind()) {
            return $this;
        }

        if ($this->hasFullHouse()) {
            return $this;
        }

        if ($this->hasFlush()) {
            return $this;
        }

        if ($this->hasStraight()) {
            return $this;
        }

        if ($this->hasThreeOfAKind()) {
            return $this;
        }

        if ($this->hasTwoPair()) {
            return $this;
        }

        if ($this->hasPair()) {
            return $this;
        }

        return $this->highestCard();
    }

    private function checkFlushForAceKicker(array $activeCards): mixed
    {
        if (in_array(1, $activeCards)) {
            $activeCardsLessThanAce = array_filter($activeCards, function ($value) {
                return Rank::ACE_RANK_ID !== $value;
            });

            return max($activeCardsLessThanAce);
        }

        return false;
    }

    private function checkForHighAceActiveCardRanking(array $rank): int|bool
    {
        if (Rank::ACE_RANK_ID === $rank['ranking']) {
            return Rank::ACE_HIGH_RANK_ID;
        }

        return false;
    }

    /**
     * @param array|object $haystack
     */
    private function getMax($haystack, string $columm): mixed
    {
        return max(array_column($haystack, $columm));
    }

    /**
     * @param array|object $haystack
     * @param string       $columm
     */
    private function getMin($haystack, $columm): mixed
    {
        return min(array_column($haystack, $columm));
    }

    private function getKicker(?array $activeCards = null): ?int
    {
        $cardRankings = array_column($this->allCardsDesc, 'ranking');

        /*
         * Check against $this->highCard & activeCards so only
         * inactive cards are used as kickers.
         *
         * @todo This won't yet cover all cases as it will return
         * null if none of the player's inactive cards meet the
         * two conditions.
         */
        foreach ($cardRankings as $cardRanking) {
            if (
                ($this->highCard && $cardRanking != $this->highCard)
                || ($activeCards && !in_array($cardRanking, $activeCards))
            ) {
                return $cardRanking;
            }
        }

        return null;
    }

    private function filterAllCards(string $column, mixed $filter): array
    {
        return array_filter($this->allCards, function ($value) use ($column, $filter) {
            return $value[$column] === $filter;
        });
    }

    private function sortAllCardsByDescRanking(): array
    {
        usort($this->allCards, function ($a, $b) {
            if ($a['ranking'] == $b['ranking']) {
                return 0;
            }

            return $a['ranking'] > $b['ranking'] ? -1 : 1;
        });

        return $this->allCards;
    }

    private function removeDuplicates(array $cards): array
    {
        $seen = [];
        $unique = [];

        foreach ($cards as $card) {
            $rank = $card['ranking'];

            if (!isset($seen[$rank])) {
                $seen[$rank] = true;
                $unique[] = $card;
            }
        }

        return $unique;
    }

    private function filterStraightCards(array $cards): array
    {
        $straight = [];

        foreach ($cards as $card) {
            if (empty($straight)) {
                $straight[] = $card;

                continue;
            }

            $last = end($straight);

            if ($last['ranking'] - 1 === $card['ranking']) {
                $straight[] = $card;

                if (5 === count($straight)) {
                    return $straight;
                }
            } else {
                $straight = [$card];
            }
        }

        return [];
    }

    public function highestCard(): self
    {
        if (1 === $this->getMin($this->allCards, 'ranking')) {
            $this->highCard = Rank::ACE_HIGH_RANK_ID;
        } else {
            $this->highCard = $this->getMax($this->allCards, 'ranking');
        }

        $this->identifiedHandType['handType'] = HandType::HIGH_CARD;
        $this->identifiedHandType['activeCards'][] = $this->highCard;
        $this->identifiedHandType['kicker'] = $this->getKicker();

        return $this;
    }

    private function checkPairs(): void
    {
        foreach (Rank::ALL as $rank) {
            if (2 !== count($this->filterAllCards('rank_id', $rank['rank_id']))) {
                continue;
            }

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

    public function hasPair(): bool
    {
        if (1 > count($this->pairs)) {
            return false;
        }

        $this->identifiedHandType['handType'] = HandType::PAIR;

        return true;
    }

    public function hasTwoPair(): bool
    {
        $this->checkPairs();

        if (2 > count($this->pairs)) {
            return false;
        }

        $this->identifiedHandType['handType'] = HandType::TWO_PAIR;

        return true;
    }

    public function hasThreeOfAKind(): bool
    {
        foreach (Rank::ALL as $rank) {
            if (3 !== count($this->filterAllCards('rank_id', $rank['rank_id']))) {
                continue;
            }

            $this->threeOfAKind = $rank;
            $this->identifiedHandType['handType'] = HandType::TRIPS;
            $this->identifiedHandType['activeCards'][] = $this->checkForHighAceActiveCardRanking($rank) ?: $rank['ranking'];
            $this->identifiedHandType['kicker'] = $this->getKicker($this->identifiedHandType['activeCards']);

            return true;
        }

        /* @todo There could be 2 trips - add handling for this */
        return false;
    }

    public function hasStraight(): bool
    {
        if ($this->hasFiveHighStraight()) {
            return true;
        }

        if ($this->hasAceHighStraight()) {
            return true;
        }

        if ($this->hasAnyOtherStraight()) {
            return true;
        }

        return false;
    }

    private function hasFiveHighStraight(): bool
    {
        return $this->hasThisStraight(
            straightCards: [Rank::ACE_RANK_ID, Rank::DEUCE_RANK_ID, Rank::THREE_RANK_ID, Rank::FOUR_RANK_ID, Rank::FIVE_RANK_ID],
            kicker: Rank::FIVE_RANK_ID
        );
    }

    private function hasAceHighStraight(): bool
    {
        return $this->hasThisStraight(
            straightCards: [Rank::ACE_RANK_ID, Rank::KING_RANK_ID, Rank::QUEEN_RANK_ID, Rank::JACK_RANK_ID, Rank::TEN_RANK_ID],
            kicker: Rank::ACE_HIGH_RANK_ID
        );
    }

    private function hasThisStraight(array $straightCards, ?int $kicker): bool
    {
        $validRanks = $straightCards;

        $straight = [];
        $rankSet = [];

        foreach ($this->allCardsDesc as $card) {
            $rank = $card['ranking'];

            if (!in_array($rank, $validRanks) || in_array($rank, $rankSet)) {
                continue;
            }

            $rankSet[] = $rank;
            $straight[] = $card;

            if (5 === count($straight)) {
                $this->straight = $straight;
                $this->identifiedHandType['handType'] = HandType::STRAIGHT;
                $this->identifiedHandType['activeCards'] = array_column($straight, 'ranking');
                $this->identifiedHandType['kicker'] = $kicker;

                return true;
            }
        }

        return false;
    }

    private function hasAnyOtherStraight(): bool
    {
        $removeDuplicates = $this->removeDuplicates($this->allCardsDesc);

        $straight = $this->filterStraightCards($removeDuplicates);

        if (empty($straight)) {
            return false;
        }

        $this->straight = $straight;
        $this->identifiedHandType['handType'] = HandType::STRAIGHT;
        $this->identifiedHandType['activeCards'] = array_column($straight, 'ranking');
        $this->identifiedHandType['kicker'] = array_shift($straight)['ranking'];

        return true;
    }

    public function hasFlush(): bool
    {
        foreach (Suit::ALL as $suit) {
            $flushCards = $this->filterAllCards('suit_id', $suit['suit_id']);

            if (5 > count($flushCards)) {
                continue;
            }

            $this->flush = $flushCards;
            $this->identifiedHandType['activeCards'] = array_column($flushCards, 'ranking');
            $this->identifiedHandType['handType'] = HandType::FLUSH;
            $this->identifiedHandType['kicker'] = $this->checkFlushForAceKicker($this->identifiedHandType['activeCards'])
                ?: array_shift($this->identifiedHandType['activeCards']);

            return true;
        }

        return false;
    }

    public function hasFullHouse(): bool
    {
        $this->checkTripsForFullHouse()->checkPairsForFullHouse();

        if ($this->threeOfAKind && 1 <= count($this->pairs)) {
            $this->fullHouse = true;
            $this->identifiedHandType['handType'] = HandType::FULL_HOUSE;
            $this->identifiedHandType['kicker'] = max($this->pairs);

            return true;
        }

        $this->pairs = [];
        $this->threeOfAKind = [];
        $this->identifiedHandType['activeCards'] = [];

        return false;
    }

    private function checkTripsForFullHouse(): self
    {
        foreach (Rank::ALL as $rank) {
            if (3 === count($this->filterAllCards('rank_id', $rank['rank_id']))) {
                $this->threeOfAKind = $rank;
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

    public function hasFourOfAKind(): bool
    {
        foreach (Rank::ALL as $rank) {
            if (4 !== count($this->filterAllCards('rank_id', $rank['rank_id']))) {
                continue;
            }

            $this->fourOfAKind = $rank;
            $this->identifiedHandType['handType'] = HandType::QUADS;
            $this->identifiedHandType['activeCards'][] = $this->checkForHighAceActiveCardRanking($rank) ?: $rank['ranking'];
            $this->identifiedHandType['kicker'] = $this->getKicker($this->identifiedHandType['activeCards']);

            return true;
        }

        return false;
    }

    public function hasStraightFlush(): bool
    {
        foreach (Suit::ALL as $suit) {
            $onlyThisSuit = [];

            /* Remove cards not in this suit. This also takes care of duplicates. */
            foreach ($this->allCardsDesc as $card) {
                if ($card['suit_id'] === $suit['suit_id']) {
                    $onlyThisSuit[] = $card;
                }
            }

            $straightFlush = $this->filterStraightCards($onlyThisSuit);

            if (empty($straightFlush)) {
                continue;
            }

            $this->straightFlush = $straightFlush;
            $this->identifiedHandType['handType'] = HandType::STRAIGHT_FLUSH;
            $this->identifiedHandType['activeCards'][] = $straightFlush;
            $this->identifiedHandType['kicker'] = $this->getMax($straightFlush, 'ranking');

            return true;
        }

        return false;
    }

    public function hasRoyalFlush(): bool
    {
        $royalRanks = [Rank::ACE_RANK_ID, Rank::KING_RANK_ID, Rank::QUEEN_RANK_ID, Rank::JACK_RANK_ID, Rank::TEN_RANK_ID];

        foreach (Suit::ALL as $suit) {
            $royalFlush = array_filter($this->allCards, function ($value) use ($suit, $royalRanks) {
                return $value['suit_id'] === $suit['suit_id'] && in_array($value['rank_id'], $royalRanks);
            });

            if (empty($royalFlush) || 5 !== count($royalFlush)) {
                continue;
            }

            $this->royalFlush = $royalFlush;
            $this->identifiedHandType['activeCards'] = array_column($this->royalFlush, 'ranking');
            $this->identifiedHandType['handType'] = HandType::ROYAL_FLUSH;

            return true;
        }

        return false;
    }
}
