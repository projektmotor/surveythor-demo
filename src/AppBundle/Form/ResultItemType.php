<?php

namespace AppBundle\Form;

use AppBundle\Entity\ResultItem;
use AppBundle\Entity\ResultItems\MultipleChoiceAnswer;
use AppBundle\Entity\ResultItems\ResultTextItem;
use AppBundle\Entity\ResultItems\SingleChoiceAnswer;
use AppBundle\Entity\ResultItems\TextAnswer;
use AppBundle\Form\ResultItems\MultipleChoiceAnswerType;
use AppBundle\Form\ResultItems\ResultTextItemType;
use AppBundle\Form\ResultItems\SingleChoiceAnswerType;
use AppBundle\Form\ResultItems\TextAnswerType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
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
        /** @var ResultItem $resultItem */
        $resultItem = $options['data'];

        switch (true) {
            case $resultItem->getContent() instanceof MultipleChoiceAnswer:
                $question = $resultItem->getContent()->getQuestion();
                $builder->add(
                    'multipleChoiceAnswer',
                    MultipleChoiceAnswerType::class,
                    array(
                        'label' => $question->getText(),
                    )
                );
                break;

            case $resultItem->getContent() instanceof SingleChoiceAnswer:
                $question = $resultItem->getContent()->getQuestion();
                $builder->add(
                    'singleChoiceAnswer',
                    SingleChoiceAnswerType::class,
                    [
                        'label' => $question->getText(),
                    ]
                );
                break;

            case $resultItem->getContent() instanceof TextAnswer:
                $question = $resultItem->getContent()->getQuestion();
                $builder->add(
                    'textAnswer',
                    TextAnswerType::class,
                    [
                        'label' => $question->getText(),
                    ]
                );
                break;

            case $resultItem->getContent() instanceof ResultTextItem:
                $builder->add(
                    'resultTextItem',
                    ResultTextItemType::class,
                    [
                        'label' => false,
                    ]
                );
                break;

            case $resultItem->getContent() instanceof ArrayCollection:
                $type = ResultItemCollectionType::class;
                // begin no really clue what this is doing
                if ($parent = $resultItem->getParentItem()) {
                    if (!is_null($parent->getSurveyItem()->getTemplate())) {
                        $type = !is_null($parent->getSurveyItem()->getTemplate()->getFormType())
                            ? $parent->getSurveyItem()->getTemplate()->getFormType()
                            : $type;
                    }
                }
                // end no really clue what this is doing

                $builder->add(
                    'childItems',
                    CollectionType::class,
                    [
                        'entry_type' => ResultItemType::class,
                        'label' => false,
                    ]
                );
                break;

            default:
                throw new \Exception('unsupported content type '.get_class($resultItem->getContent()));
                break;
        }
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
