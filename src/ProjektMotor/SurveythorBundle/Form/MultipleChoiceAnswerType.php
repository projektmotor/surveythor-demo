<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PM\SurveythorBundle\Entity\Choice;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * MultipleChoiceAnswerType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class MultipleChoiceAnswerType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_answer';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, array(
                'attr' => array('class' => 'title-field')
            ))
            ->add('value', TextType::class, array(
                'label' => 'points'
            ))
            ->add('childQuestions', QuestionCollectionType::class, array(
                'label' => false,
                'entry_type' => QuestionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => array(
                    'label' => false
                ),
                'prototype_name' => '__question__',
                'attr' => array('class' => 'child-question-prototype sortable')
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Choice::class
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::FORM_NAME;
    }
}
