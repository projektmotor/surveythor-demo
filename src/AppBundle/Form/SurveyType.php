<?php

namespace AppBundle\Form;

use AppBundle\Entity\Survey;
use AppBundle\Form\SurveyItems\QuestionCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SurveyType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Beschreibung',
                    'required' => false,
                ]
            )
            ->add(
                'surveyItems',
                QuestionCollectionType::class,
                [
                    'entry_type' => SurveyItemType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false,
                    ],
                    'prototype_name' => '__surveyitem__',
                    'attr' => ['class' => 'sortable'],
                ]
            )
            ->add(
                'resultRanges',
                ResultRangeCollectionType::class,
                [
                    'entry_type' => ResultRangeType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'label' => false,
                    ],
                    'prototype_name' => '__resultRange__',
                    'label' => false,
                ]
            )
            ->add('submit', SubmitType::class, ['label' => 'Speichern']);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Survey::class,
            ]
        );
    }
}
