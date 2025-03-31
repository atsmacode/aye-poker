<?php

$finder = (new PhpCsFixer\Finder())
    ->in('modules/framework/app')
    ->in('modules/framework/tests')
    ->in('modules/card-games/app')
    ->in('modules/card-games/tests')
    ->in('modules/poker-game/app')
    ->in('modules/poker-game/tests')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
