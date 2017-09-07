<?php
namespace PM\SurveythorBundle\Form\SurveyItems;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * QuestionCollectionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class QuestionCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'question_collection';
    }
}
