<?php

namespace AppBundle\Entity\Factory;

use AppBundle\Entity\SurveyItems\ItemGroup;
use AppBundle\Entity\SurveyItems\Question;
use AppBundle\Entity\SurveyItems\SurveyTextItem;

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
                $item = new SurveyTextItem();
                break;
            case 'itemGroup':
                $item = new ItemGroup();
                break;
        }

        return $item;
    }
}
