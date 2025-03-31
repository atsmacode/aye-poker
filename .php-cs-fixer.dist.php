<?php

$finder = (new PhpCsFixer\Finder())
    ->in('modules/framework/app')
    ->in('modules/card-games/app')
    ->in('modules/poker-game/app')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
