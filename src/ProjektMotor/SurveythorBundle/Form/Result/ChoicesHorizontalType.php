<?php
namespace PM\SurveythorBundle\Form\Result;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

/**
 * ChoicesHorizontalType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ChoicesHorizontalType extends AbstractType
{
    public function getParent()
    {
        return EntityType::class;
    }

    public function getBlockPrefix()
    {
        return 'choices_horizontal';
    }
}
