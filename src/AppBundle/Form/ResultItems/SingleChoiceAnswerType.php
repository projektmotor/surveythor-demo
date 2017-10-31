<?php

namespace AppBundle\Form\ResultItems;

use AppBundle\Entity\Choice;
use AppBundle\Entity\ResultItems\SingleChoiceAnswer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SingleChoiceAnswerType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SingleChoiceAnswerType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var SingleChoiceAnswer $answer */
                $answer = $event->getData();
                $form = $event->getForm();

                if ($answer) {
                    $question = $answer->getQuestion();

                    $type = EntityType::class;
                    if (!is_null($question->getQuestionTemplate())) {
                        $type = !is_null($question->getQuestionTemplate()->getFormType())
                            ? $question->getQuestionTemplate()->getFormType()
                            : $type;
                    }

                    $form->add(
                        'choice',
                        $type,
                        [
                            'label' => false,
                            'class' => Choice::class,
                            'choice_label' => 'text',
                            'choices' => $question->getChoices(),
                            'expanded' => true,
                            'by_reference' => false,
                            'attr' => [
                                'class' => 'choice-answer',
                            ],
                            'choice_attr' => function ($val, $key, $index) {
                                return [
                                    'data-answer-id' => $val->getId(),
                                ];
                            },
                        ]
                    );
                }
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'question' => null,
                'data_class' => SingleChoiceAnswer::class,
            ]
        );
    }
}
