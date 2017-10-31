<?php

namespace AppBundle\Form;

use AppBundle\Entity\ResultRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ResultRangeType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultRangeType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'meaning',
                TextareaType::class,
                [
                    'label' => 'Beschreibung (wird nicht angezeigt)',
                ]
            )
            ->add(
                'start',
                NumberType::class,
                [
                    'label' => 'minimale Punktzahl',
                ]
            )
            ->add(
                'stop',
                NumberType::class,
                [
                    'label' => 'maximale Punktzahl',
                ]
            )
            ->add(
                'event',
                ChoiceType::class,
                [
                    'choices' => [
                        'Redirect zu Webseite' => 0,
                        'Text anzeigen' => 1,
                    ],
                    'label' => 'Aktion',
                ]
            );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ResultRange::class,
            ]
        );
    }

}
