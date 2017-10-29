<?php

namespace AppBundle\Form\Event;

use AppBundle\Entity\SurveyItems\ItemGroup;
use AppBundle\Entity\SurveyItems\Question;
use AppBundle\Entity\SurveyItems\SurveyTextItem;
use AppBundle\Form\SurveyItems\ItemGroupType;
use AppBundle\Form\SurveyItems\QuestionType;
use AppBundle\Form\SurveyItems\TextItemType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

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
