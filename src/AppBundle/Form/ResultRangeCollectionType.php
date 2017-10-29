<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * ResultRangeCollectionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultRangeCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'resultrange_collection';
    }
}
