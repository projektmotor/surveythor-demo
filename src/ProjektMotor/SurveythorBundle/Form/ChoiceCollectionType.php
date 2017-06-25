<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * AnswerCollectionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ChoiceCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'choice_collection';
    }
}
