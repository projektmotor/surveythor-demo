<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PM\SurveythorBundle\Entity\Answer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * AnswerType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class AnswerType extends AbstractType
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
            ->add('points')
            ->add('childQuestions', QuestionCollectionType::class, array(
                'label' => false,
                'entry_type' => ChildQuestionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'entry_options' => array(
                    'label' => false
                ),
                'prototype_name' => '__child_question__',
                'attr' => array('class' => 'child-question-prototype')
            ))

        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Answer::class
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
