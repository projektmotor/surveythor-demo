<?php
namespace PM\SurveythorBundle\Form\SurveyItems;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * SurveyItemCollectionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyItemCollectionType extends AbstractType
{
    public function getParent()
    {
        return CollectionType::class;
    }

    public function getBlockPrefix()
    {
        return 'surveyitem_collection';
    }
}
