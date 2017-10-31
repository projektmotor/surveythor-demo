<?php

namespace AppBundle\Form\ResultItems;

use AppBundle\Entity\ResultItems\ResultTextItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TextItemType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultTextItemType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', HiddenType::class, ['label' => false]);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ResultTextItem::class,
            ]
        );
    }
}
