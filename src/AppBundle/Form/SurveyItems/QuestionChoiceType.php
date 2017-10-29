<?php

namespace AppBundle\Form\SurveyItems;

use AppBundle\Entity\Choice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * QuestionChoiceType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class QuestionChoiceType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_answer';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', TextType::class, array(
                'attr' => array('class' => 'title-field'),
                'label' => 'Antwort'
            ))
            ->add('value', TextType::class, array(
                'label' => 'Punkte'
            ))
            ->add('sortOrder', HiddenType::class, array(
                'attr' => array('class' => 'sortorder')
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

    public function getBlockPrefix()
    {
        return 'backend_choice';
    }
}
