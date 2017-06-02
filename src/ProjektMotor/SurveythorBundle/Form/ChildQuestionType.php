<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PM\SurveythorBundle\Entity\Dto\Question;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * ChildQuestionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ChildQuestionType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_child_question';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, array(
                'attr' => array('class' => 'title-field')
            ))
            ->add('answers', CollectionType::class, array(
                'entry_type' => ChildQuestionAnswerType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true,
                'entry_options' => array(
                    'label' => false
                ),
                'prototype_name' => '__child_question_answer__',
                'attr' => array('class' => 'child-question-answer-prototype')
            ))
        ;

    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ''
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
