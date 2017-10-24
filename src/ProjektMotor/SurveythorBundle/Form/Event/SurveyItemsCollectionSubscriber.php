<?php
namespace PM\SurveythorBundle\Form\Event;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvents;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\SurveyTextItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Form\SurveyItems\QuestionType;
use PM\SurveythorBundle\Form\SurveyItems\TextItemType;
use PM\SurveythorBundle\Form\SurveyItems\ItemGroupType;

/**
 * SurveyItemsCollectionSubscriber
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyItemsCollectionSubscriber implements EventSubscriberInterface
{
    protected $factory;

    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'setSurveyItemType'
        );
    }

    public function setSurveyItemType(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($data === null || '' === $data) {
            return;
        }

        $toAdd = array();
        foreach ($form as $name => $child) {
            $toAdd[$name] = $child->getConfig()->getOptions();
            $form->remove($name);
        }
            switch (true) {
                case $data instanceof Question:
                    $type = QuestionType::class;
                    break;
                case $data instanceof SurveyTextItem:
                    $type = TextItemType::class;
                    break;
                case $data instanceof ItemGroup:
                    $type = ItemGroupType::class;
                    break;
            }

            $form->add($this->factory->createNamed($name, $type, null));

        #dump($data); die();
    }
}
