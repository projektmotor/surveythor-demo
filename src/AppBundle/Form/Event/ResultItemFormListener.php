<?php

namespace AppBundle\Form\Event;

use AppBundle\Entity\ResultItems\MultipleChoiceAnswer;
use AppBundle\Entity\ResultItems\ResultTextItem;
use AppBundle\Entity\ResultItems\SingleChoiceAnswer;
use AppBundle\Entity\ResultItems\TextAnswer;
use AppBundle\Form\ResultItems\MultipleChoiceAnswerType;
use AppBundle\Form\ResultItems\ResultTextItemType;
use AppBundle\Form\ResultItems\SingleChoiceAnswerType;
use AppBundle\Form\ResultItems\TextAnswerType;
use AppBundle\Form\ResultItemType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ResultItemFormListener implements EventSubscriberInterface
{
    /**
     * @var array
     */
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
        );
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (null === $data) {
            $data = array();
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }

        // First remove all rows
        foreach ($form as $name => $child) {
            $form->remove($name);
        }

        // Then add all rows again in the correct order
        foreach ($data as $name => $value) {
            if ($value instanceof MultipleChoiceAnswer) {
                $form->add(
                    'multipleChoiceAnswer',
                    MultipleChoiceAnswerType::class,
                    array(
                        'label' => $value->getText(),
                    )
                );
            } elseif ($value instanceof SingleChoiceAnswer) {
                $form->add(
                    'singleChoiceAnswer',
                    SingleChoiceAnswerType::class,
                    [
                        'label' => $value->getText(),
                    ]
                );
            } elseif ($value instanceof TextAnswer) {
                $form->add(
                    'textAnswer',
                    TextAnswerType::class,
                    [
                        'label' => $value->getText(),
                    ]
                );
            } elseif ($value instanceof ResultTextItem) {
                $form->add(
                    'resultTextItem',
                    ResultTextItemType::class,
                    [
                        'label' => false,
                    ]
                );
            } else {
                $options = array_replace(
                    array(
                        'property_path' => '['.$name.']',
                    ),
                    $this->options
                );
                $options['data'] = $value;
                $form->add($name, ResultItemType::class, $options);
            }
        }
    }
}
