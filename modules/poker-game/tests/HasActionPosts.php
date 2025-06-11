<?php

namespace Atsmacode\PokerGame\Tests;

use Atsmacode\PokerGame\Constants\Action;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\PlayerActionController as PotLimitHoldEmPlayerActionController;
use Atsmacode\PokerGame\Controllers\PotLimitHoldEm\SitController as PotLimitHoldEmSitController;
use Symfony\Component\HttpFoundation\Request;

trait HasActionPosts
{
    private function actionControllerResponse(Request $request)
    {
        $response = (new PotLimitHoldEmPlayerActionController($this->gamePlayService))->action($request);

        return json_decode($response->getContent(), true);
    }

    private function sitControllerResponse(): array
    {
        $request = Request::create(
            uri: '',
            method: 'POST',
            content: json_encode(['tableId' => $this->testTable->getId()])
        );

        $response = (new PotLimitHoldEmSitController($this->gamePlayService))->sit(
            $request,
            $this->playerOne->getId()
        );

        return json_decode($response->getContent(), true);
    }

    private function setPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[0]['player_action_id'],
            'action_id' => Action::CALL_ID,
            'bet_amount' => 50,
            'active' => 1,
            'stack' => $this->gameState->getPlayers()[0]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerOneFoldsPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[0]['player_action_id'],
            'action_id' => Action::FOLD_ID,
            'bet_amount' => null,
            'active' => 0,
            'stack' => $this->gameState->getPlayers()[0]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerTwoCallsPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[1]['player_action_id'],
            'action_id' => Action::CALL_ID,
            'bet_amount' => 50,
            'active' => 1,
            'stack' => $this->gameState->getPlayers()[1]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerTwoChecksPost(?int $streetNumber = null)
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[1]['player_action_id'],
            'action_id' => Action::CHECK_ID,
            'bet_amount' => null,
            'active' => 1,
            'stack' => $this->gameState->getPlayers()[1]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerTwoFoldsPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[1]['player_action_id'],
            'action_id' => Action::FOLD_ID,
            'bet_amount' => null,
            'active' => 0,
            'stack' => $this->gameState->getPlayers()[1]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerThreeChecksPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[2]['player_action_id'],
            'action_id' => Action::CHECK_ID,
            'bet_amount' => null,
            'active' => 1,
            'stack' => $this->gameState->getPlayers()[2]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerThreeRaisesPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[2]['player_action_id'],
            'action_id' => Action::RAISE_ID,
            'bet_amount' => 100,
            'active' => 1,
            'stack' => $this->gameState->getPlayers()[2]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerFourCallsPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[3]['player_action_id'],
            'action_id' => Action::CALL_ID,
            'bet_amount' => 50,
            'active' => 1,
            'stack' => $this->gameState->getPlayers()[3]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerFourFoldsPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[3]['player_action_id'],
            'action_id' => Action::FOLD_ID,
            'bet_amount' => null,
            'active' => 0,
            'stack' => $this->gameState->getPlayers()[3]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerFourRaisesPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[3]['player_action_id'],
            'action_id' => Action::RAISE_ID,
            'bet_amount' => 100,
            'active' => 1,
            'stack' => $this->gameState->getPlayers()[3]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerFourChecksPost()
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[3]['player_action_id'],
            'action_id' => Action::CHECK_ID,
            'bet_amount' => null,
            'active' => 1,
            'stack' => $this->gameState->getPlayers()[3]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerSixFoldsPost(?int $streetNumber = null)
    {
        $requestBody = [
            'player_action_id' => $this->gameState->getPlayers()[5]['player_action_id'],
            'action_id' => Action::FOLD_ID,
            'bet_amount' => null,
            'active' => 0,
            'stack' => $this->gameState->getPlayers()[5]['stack'],
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }
}
