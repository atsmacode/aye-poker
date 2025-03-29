<?php

$finder = (new PhpCsFixer\Finder())
    ->in('modules/poker-game/app')
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
