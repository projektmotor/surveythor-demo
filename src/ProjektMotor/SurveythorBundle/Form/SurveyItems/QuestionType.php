<?php
namespace PM\SurveythorBundle\Form\SurveyItems;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\QuestionTemplate;

/**
 * QuestionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class QuestionType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_question';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', null, array(
                'label' => 'Frage'
            ))
        ;
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $question = $event->getData();

                if (!is_null($question)) {
                    $type = $question->getType();
                    $form = $event->getForm();

                    if ($type == 'mc' || $type == 'sc') {
                        $form->add('choices', ChoiceCollectionType::class, array(
                            'entry_type' => QuestionChoiceType::class,
                            'allow_add' => true,
                            'allow_delete' => true,
                            'by_reference' => false,
                            'entry_options' => array(
                                'label' => false
                            ),
                            'label' => 'Antworten',
                            'prototype_name' => '__choice__',
                            'attr' => array('class' => 'question-answer-prototype sortable')
                        ));
                        $form->add('template', EntityType::class, array(
                            'class' => QuestionTemplate::class,
                            'required' => false,
                            'choice_label' => 'name',
                            'label' => 'Choices Layout'
                        ));
                    }
                }
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Question::class
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
