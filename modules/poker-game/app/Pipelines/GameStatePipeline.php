<?php

namespace Atsmacode\PokerGame\Pipelines;

use Atsmacode\PokerGame\Contracts\ProcessesGameState;
use Atsmacode\PokerGame\State\Game\GameState;
use Psr\Container\ContainerInterface;

class GameStatePipeline
{
    /**
     * @var array<string>
     */
    private array $steps;

    public function __construct(private ContainerInterface $container)
    {
    }

    public function add(array $steps): self
    {
        $this->steps = $steps;

        return $this;
    }

    public function run(GameState $gameState): GameState
    {
        foreach ($this->steps as $class) {
            $step = $this->container->build($class); // @phpstan-ignore method.notFound

            if (!$step instanceof ProcessesGameState) {
                throw new \Exception(sprintf('Pipeline not instance of %s', ProcessesGameState::class));
            }

            $gameState = $step->process($gameState);
        }

        return $gameState;
    }
}