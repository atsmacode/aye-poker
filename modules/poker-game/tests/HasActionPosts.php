<?php

namespace Atsmacode\PokerGame\Tests;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\PlayerActionController as PotLimitHoldEmPlayerActionController;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\SitController as PotLimitHoldEmSitController;
use Symfony\Component\HttpFoundation\Request;

trait HasActionPosts
{
    use HasRequests;

    private function actionControllerResponse(Request $request)
    {
        $response = (new PotLimitHoldEmPlayerActionController($this->gamePlayService))->action($request);

        return json_decode($response->getContent(), true);
    }

    private function sitControllerResponse(): array
    {
        $request = $this->post([
            'tableId' => $this->testTable->getId(),
            'gameId' => $this->testGame->getId(),
        ]);

        $response = (new PotLimitHoldEmSitController($this->gamePlayService))->sit($request);

        return json_decode($response->getContent(), true);
    }

    private function setPost()
    {
        $player = $this->gameState->getPlayer($this->playerOne->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::CALL_ID,
            'bet_amount' => 50,
            'active' => 1,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerOneFoldsPost()
    {
        $player = $this->gameState->getPlayer($this->playerOne->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::FOLD_ID,
            'bet_amount' => null,
            'active' => 0,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerTwoCallsPost()
    {
        $player = $this->gameState->getPlayer($this->playerTwo->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::CALL_ID,
            'bet_amount' => 50,
            'active' => 1,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerTwoChecksPost()
    {
        $player = $this->gameState->getPlayer($this->playerTwo->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::CHECK_ID,
            'bet_amount' => null,
            'active' => 1,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerTwoFoldsPost()
    {
        $player = $this->gameState->getPlayer($this->playerTwo->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::FOLD_ID,
            'bet_amount' => null,
            'active' => 0,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerThreeChecksPost()
    {
        $player = $this->gameState->getPlayer($this->playerThree->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::CHECK_ID,
            'bet_amount' => null,
            'active' => 1,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerThreeRaisesPost()
    {
        $player = $this->gameState->getPlayer($this->playerThree->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::RAISE_ID,
            'bet_amount' => 100,
            'active' => 1,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerFourCallsPost()
    {
        $player = $this->gameState->getPlayer($this->playerFour->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::CALL_ID,
            'bet_amount' => 50,
            'active' => 1,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerFourFoldsPost()
    {
        $player = $this->gameState->getPlayer($this->playerFour->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::FOLD_ID,
            'bet_amount' => null,
            'active' => 0,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerFourRaisesPost()
    {
        $player = $this->gameState->getPlayer($this->playerFour->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::RAISE_ID,
            'bet_amount' => 100,
            'active' => 1,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerFourChecksPost()
    {
        $player = $this->gameState->getPlayer($this->playerFour->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::CHECK_ID,
            'bet_amount' => null,
            'active' => 1,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }

    private function setPlayerSixFoldsPost(?int $streetNumber = null)
    {
        $player = $this->gameState->getPlayer($this->playerSix->getId());

        $requestBody = [
            'player_action_id' => $player['player_action_id'],
            'action_id' => Action::FOLD_ID,
            'bet_amount' => null,
            'active' => 0,
            'stack' => $player['stack'],
        ];

        return $this->post($requestBody);
    }
}
