<?php

namespace App\Form;

use App\Entity\Games;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class EditGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('game_name', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom du jeu',
                    'class' => 'input__style',
                ]
            ])
            ->add('game_price', NumberType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Number',
                    'class' => 'input__style',
                ]
            ])
            ->add('game_img', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Number',
                    'class' => 'input__style',
                    'type' => 'number'
                ]
            ])
            ->add('game_desc', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Number',
                    'class' => 'game__desc',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Games::class,
        ]);
    }
}
