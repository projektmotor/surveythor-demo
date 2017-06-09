<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * ResultAnswerCollectionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultAnswerCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'result_answer_collection';
    }
}
