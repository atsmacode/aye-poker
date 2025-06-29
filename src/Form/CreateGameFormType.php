<?php

namespace App\Form;

use App\Entity\UserPlayer;
use Atsmacode\PokerGame\Enums\GameMode;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateGameFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mode', ChoiceType::class, [
                'choices' => [
                    GameMode::TEST->display() => GameMode::TEST->value,
                    GameMode::REAL->display() => GameMode::REAL->value
                ]
            ])
            ->add('player_count', ChoiceType::class, [
                'choices' => ['2' => 2, '3' => 3, '4' => 4, '5' => 6],
            ])
            ->add('players', EntityType::class, [
                'class' => UserPlayer::class,
                'choice_value' => 'player_id',
                'choice_label' => function (UserPlayer $userPlayer) {
                    return $userPlayer->getPlayerName() ?? 'Unknown Player';
                },
                'placeholder' => 'Select players',
                'required' => false,
                'multiple' => true,
            ]);
        ;
    }
}
