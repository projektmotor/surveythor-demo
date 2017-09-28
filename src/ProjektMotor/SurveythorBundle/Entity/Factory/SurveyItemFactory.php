<?php
namespace PM\SurveythorBundle\Entity\Factory;

use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;

/**
 * SurveyItemFactory
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyItemFactory
{
    public static function createByType($type)
    {
        switch ($type) {
            case 'singleChoice':
                $item = new Question();
                $item->setType('sc');
                break;
            case 'multipleChoice':
                $item = new Question();
                $item->setType('mc');
                break;
            case 'textQuestion':
                $item = new Question();
                $item->setType('text');
                break;
            case 'textItem':
                $item = new TextItem();
                break;
            case 'itemGroup':
                $item = new ItemGroup();
                break;
        }

        return $item;
    }
}
