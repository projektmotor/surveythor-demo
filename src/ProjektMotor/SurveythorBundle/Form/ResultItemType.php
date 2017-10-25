<?php
namespace PM\SurveythorBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\ResultItems\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextAnswer;
use PM\SurveythorBundle\Entity\ResultItems\ResultTextItem;
use PM\SurveythorBundle\Form\ResultItems\MultipleChoiceAnswerType;
use PM\SurveythorBundle\Form\ResultItems\SingleChoiceAnswerType;
use PM\SurveythorBundle\Form\ResultItems\TextAnswerType;
use PM\SurveythorBundle\Form\ResultItems\TextItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ResultItemType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultItemType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_resultitem';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var ResultItem $resultItem */
            $resultItem = $event->getData();
            $form = $event->getForm();

            if ($resultItem) {
                switch (true) {
                    case $resultItem->getContent() instanceof MultipleChoiceAnswer:
                        $question = $resultItem->getContent()->getQuestion();
                        $form->add('multipleChoiceAnswer', MultipleChoiceAnswerType::class, array(
                            'label' => $question->getText()
                        ));
                        break;

                    case $resultItem->getContent() instanceof SingleChoiceAnswer:
                        $question = $resultItem->getContent()->getQuestion();
                        $form->add(
                            'singleChoiceAnswer',
                            SingleChoiceAnswerType::class,
                            [
                                'label' => $question->getText(),
                            ]
                        );
                        break;

                    case $resultItem->getContent() instanceof TextAnswer:
                        $question = $resultItem->getContent()->getQuestion();
                        $form->add('textAnswer', TextAnswerType::class, [
                            'label' => $question->getText()
                        ]);
                        break;

                    case $resultItem->getContent() instanceof ResultTextItem:
                        $form->add('textItem', TextItemType::class, [
                            'label' => false
                        ]);
                        break;

                    case $resultItem->getContent() instanceof ArrayCollection:
                    case $resultItem->getContent() instanceof PersistentCollection:
                        $type = ResultItemCollectionType::class;
                        if ($parent = $resultItem->getParentItem()) {
                            if (!is_null($parent->getSurveyItem()->getTemplate())) {
                                $type = !is_null($parent->getSurveyItem()->getTemplate()->getFormType())
                                    ? $parent->getSurveyItem()->getTemplate()->getFormType()
                                    : $type;
                            }
                        }

                        $form->add('childItems', $type, array(
                            'entry_type' => ResultItemType::class,
                            'label' => false
                        ));
                        break;

                    default:
                        dump(get_class($resultItem->getContent()));
                        dump($resultItem->getContent());
                        dump($resultItem);
                        die();
                        break;
                }
            }
        });
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
            'data_class' => ResultItem::class,
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return self::FORM_NAME;
    }
}
