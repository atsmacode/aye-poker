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
        $response = (new PotLimitHoldEmPlayerActionController($this->container, $this->actionHandler))->action($request);

        return json_decode($response->getContent(), true);
    }

    private function sitControllerResponse($currentDealer = null): array
    {
        $response = (new PotLimitHoldEmSitController($this->container))->sit($this->table->getId(), $currentDealer);

        return json_decode($response->getContent(), true);
    }

    private function sitControllerResponseWithPlayerId($currentDealer = null, int $playerId = null): array
    {
        $response = (new PotLimitHoldEmSitController($this->container))->sit(
            $this->table->getId(),
            $currentDealer,
            $playerId
        );

        return json_decode($response->getContent(), true);
    }

    private function setPost()
    {
        $requestBody = [
            'player_id'      => $this->playerOne->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[0]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::CALL_ID,
            'bet_amount'     => 50,
            'active'         => 1,
            'stack'          => $this->gameState->getPlayers()[0]['stack']
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
            'player_id'      => $this->playerOne->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[0]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::FOLD_ID,
            'bet_amount'     => null,
            'active'         => 0,
            'stack'          => $this->gameState->getPlayers()[0]['stack']
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
            'player_id'      => $this->playerTwo->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[1]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::CALL_ID,
            'bet_amount'     => 50,
            'active'         => 1,
            'stack'          => $this->gameState->getPlayers()[1]['stack']
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerTwoChecksPost(int $streetNumber = null)
    {
        $requestBody = [
            'player_id'      => $this->playerTwo->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[1]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[$streetNumber ?: 0]['id'],
            'action_id'      => Action::CHECK_ID,
            'bet_amount'     => null,
            'active'         => 1,
            'stack'          => $this->gameState->getPlayers()[1]['stack']
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
            'player_id'      => $this->playerTwo->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[1]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::FOLD_ID,
            'bet_amount'     => null,
            'active'         => 0,
            'stack'          => $this->gameState->getPlayers()[1]['stack']
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
            'player_id'      => $this->playerThree->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[2]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::CHECK_ID,
            'bet_amount'     => null,
            'active'         => 1,
            'stack'          => $this->gameState->getPlayers()[2]['stack']
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
            'player_id'      => $this->playerThree->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[2]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::RAISE_ID,
            'bet_amount'     => 100,
            'active'         => 1,
            'stack'          => $this->gameState->getPlayers()[2]['stack']
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
            'player_id'      => $this->playerFour->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[3]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::CALL_ID,
            'bet_amount'     => 50,
            'active'         => 1,
            'stack'          => $this->gameState->getPlayers()[3]['stack']
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
            'player_id'      => $this->playerFour->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[3]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::FOLD_ID,
            'bet_amount'     => null,
            'active'         => 0,
            'stack'          => $this->gameState->getPlayers()[3]['stack']
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
            'player_id'      => $this->playerFour->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[3]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::RAISE_ID,
            'bet_amount'     => 100,
            'active'         => 1,
            'stack'          => $this->gameState->getPlayers()[3]['stack']
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
            'player_id'      => $this->playerFour->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[3]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[0]['id'],
            'action_id'      => Action::CHECK_ID,
            'bet_amount'     => null,
            'active'         => 1,
            'stack'          => $this->gameState->getPlayers()[3]['stack']
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }

    private function setPlayerSixFoldsPost(int $streetNumber = null)
    {
        $requestBody = [
            'player_id'      => $this->playerSix->getId(),
            'table_seat_id'  => $this->gameState->getSeats()[5]['id'],
            'hand_street_id' => $this->gameState->updateHandStreets()->getHandStreets()[$streetNumber ?: 0]['id'],
            'action_id'      => Action::FOLD_ID,
            'bet_amount'     => null,
            'active'         => 0,
            'stack'          => $this->gameState->getPlayers()[5]['stack']
        ];

        return Request::create(
            uri: '',
            method: 'POST',
            content: json_encode($requestBody)
        );
    }
}