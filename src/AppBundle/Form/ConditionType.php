<?php

namespace AppBundle\Form;

use AppBundle\Entity\Choice;
use AppBundle\Entity\SurveyItems\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * QuestionCondtionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ConditionType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_questioncondition';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $survey = $options['item']->getSurvey();

        $builder->add('question', EntityType::class, array(
            'class' => Question::class,
            //'choices' => $survey->getChoiceQuestions(),
            'choice_label' => 'text',
            'attr' => array('class' => 'condition-question'),
            'mapped' => false,
            'label' => 'Frage'
        ));

        $questionModifier = function (FormInterface $form, Question $question) {
            if (!is_null($question)) {
                $form->remove('question');
                $form->add('question', EntityType::class, array(
                    'class' => Question::class,
                    //'choices' => $survey->getChoices(),
                    'choice_label' => 'text',
                    'attr' => array('class' => 'condition-question'),
                    'data' => $question,
                    'label' => 'Frage',
                    'mapped' => false
                ));
                if ($question->hasChoices()) {
                    $form->add('choices', EntityType::class, array(
                        'class' => Choice::class,
                        'required' => false,
                        'choice_label' => 'text',
                        'choices' => $question->getChoices(),
                        'expanded' => true,
                        'multiple' => true,
                        'label' => 'Antworten'
                    ));
                }
                $form->add('isNegative', null, array(
                    'label' => 'Element nur anzeigen, wenn obige Fragen nicht ausgewählt wurden'
                ));
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($questionModifier) {
                $data = $event->getData();

                if (!is_null($data)) {
                    $question = $data->getChoices()->first()->getQuestion();
                    $questionModifier($event->getForm(), $question);
                }
            }
        );

        $builder->get('question')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($questionModifier) {
                $question = $event->getForm()->getData();
                $questionModifier($event->getForm()->getParent(), $question);
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Condition',
            'item' => null
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
