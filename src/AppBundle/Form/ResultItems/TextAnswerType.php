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
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('value', null, ['label' => false]);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
            'question' => null,
            'data_class' => TextAnswer::class
            ]
        );
    }
}
