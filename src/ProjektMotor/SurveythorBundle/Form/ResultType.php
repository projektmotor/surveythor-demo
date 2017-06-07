<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use PM\SurveythorBundle\Entity\Result;

/**
 * ResultType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_result';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('resultAnswers', CollectionType::class, array(
            'entry_type' => ResultAnswerType::class,
            'label' => false,
            'allow_add' => true,
            'by_reference' => true,
            'entry_options' => array(
                'label' => false
            ),
            'prototype_name' => '__result-answer__',
            'attr' => array('class' => 'result-answer-prototype')
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Result::class
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
