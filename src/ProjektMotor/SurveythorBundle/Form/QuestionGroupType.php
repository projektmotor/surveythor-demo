<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use PM\SurveythorBundle\Entity\QuestionGroup;

/**
 * QuestionGroupType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class QuestionGroupType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_questiongroup';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('header', TextareaType::class)
            ->add('questions', QuestionCollectionType::class, array(
                'entry_type' => QuestionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => array(
                    'label' => false
                ),
                'prototype_name' => '__question__',
                'attr' => array('class' => 'sortable')
            ));
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => QuestionGroup::class
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
