<?php

namespace App\Form;

use Atsmacode\PokerGame\Enums\GameMode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateGameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gameMode', ChoiceType::class, [
                'choices' => [
                    'Test' => GameMode::TEST->value,
                    // 'real' => GameMode::REAL->value
                ]
            ])
            ->add('playerCount', ChoiceType::class, [
                'choices' => ['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 6],
            ])
        ;
    }
}
