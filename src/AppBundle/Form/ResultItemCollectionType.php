<?php

namespace AppBundle\Form;

use AppBundle\Form\Event\ResultItemFormListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * ResultItemCollectionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultItemCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $resultItemFormListener = new ResultItemFormListener($options['entry_options']);

        $builder->addEventSubscriber($resultItemFormListener);
    }

    public function getParent()
    {
        return CollectionType::class;
    }
}
