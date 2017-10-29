<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * ResultItemCollectionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultItemCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'resultitem_collection';
    }
}
