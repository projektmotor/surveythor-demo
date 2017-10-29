<?php

namespace AppBundle\Form\ResultItems;

use AppBundle\Entity\ResultItems\TextAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TextAnswerType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class TextAnswerType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_textanswer';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', null, array('label' => false));
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'question' => null,
            'data_class' => TextAnswer::class
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
