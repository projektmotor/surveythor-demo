<?php

namespace AppBundle\Form\Result;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * ChoicesChildrenGroupedBelowType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ChoicesChildrenGroupedBelowType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'choices_children_below';
    }
}
