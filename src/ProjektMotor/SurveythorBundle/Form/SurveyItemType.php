<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\Question;
use PM\SurveythorBundle\Entity\QuestionTemplate;
use PM\SurveythorBundle\Entity\TextItem;
use PM\SurveythorBundle\Entity\QuestionGroup;

/**
 * SurveyItemType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyItemType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_surveyitem';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();

                if (!is_null($data)) {
                    $form = $event->getForm();
                    switch (get_class($data)) {
                        case Question::class:
                            $form
                                ->add('type', ChoiceType::class, array(
                                    'choices' => array(
                                        'text question' => 'text',
                                        'single choice question' => 'sc',
                                        'multiple choice question' => 'mc'
                                    ),
                                    'attr' => array(
                                        'class' => 'question-type-select'
                                    ),
                                    'placeholder' => ''
                                ))

                                ->add('text', HiddenType::class)
                                ->add('sortOrder', HiddenType::class, array(
                                    'attr' => array('class' => 'sortorder')
                                ))
                            ;
                            break;
                        case TextItem::class:
                            $form->add('text', TextareaType::class);
                            break;
                        case QuestionGroup::class:
                            $form->add('header', TextareaType::class);
                            $form->add('questions', QuestionCollectionType::class, array(
                                'entry_type' => QuestionType::class,
                                'by_reference' => false,
                                'entry_options' => array(
                                    'label' => false
                                ),
                                'prototype_name' => '__question__',
                                'attr' => array('class' => 'sortable')
                            ));
                            $form->add('childGroups', QuestionCollectionType::class, array(
                                'entry_type' => QuestionGroupType::class,
                                'by_reference' => false,
                                'entry_options' => array(
                                    'label' => false
                                ),
                                'prototype_name' => '__questiongroup__',
                                'attr' => array('class' => 'sortable')
                            ));
                            break;
                    }
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $question = $event->getData();
                if (!is_null($question)) {
                    $form = $event->getForm();
                    $form->add('conditions', ConditionCollectionType::class, array(
                        'entry_type' => CondtionType::class,
                        'allow_add' => true,
                        'allow_delete' => true,
                        //'by_reference' => false,
                        'entry_options' => array(
                            'item' => $question,
                            'label' => false
                        ),
                        'prototype_name' => '__condition__'
                    ));
                }
            }
        );

        $typeModifier = function (FormInterface $form, $type = null) {
            if (null !== $type) {
                $form->add('text', TextareaType::class, array(
                        'attr' => array('class' => 'title-field')
                ));

                if ($type == 'mc' || $type == 'sc') {
                    $form->add('template', EntityType::class, array(
                        'class' => QuestionTemplate::class,
                        'required' => false,
                        'choice_label' => 'name',
                    ));
                    $form->add('childrenTemplate', EntityType::class, array(
                        'class' => QuestionTemplate::class,
                        'required' => false,
                        'choice_label' => 'name',
                    ));


                    $form->add('choices', ChoiceCollectionType::class, array(
                        'entry_type' => QuestionChoiceType::class,
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'entry_options' => array(
                            'label' => false
                        ),
                        'prototype_name' => '__choice__',
                        'attr' => array('class' => 'question-answer-prototype sortable')
                    ));
                }
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($typeModifier) {
                $data = $event->getData();

                if (!is_null($data)) {
                    if (get_class($data) == Question::class) {
                        $typeModifier($event->getForm(), $data->getType());
                    }
                }
            }
        );

        //$builder->get('type')->addEventListener(
        //    FormEvents::POST_SUBMIT,
        //    function (FormEvent $event) use ($typeModifier) {
        //        $type = $event->getForm()->getData();
        //        $typeModifier($event->getForm()->getParent(), $type);
        //    }
        //);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SurveyItem::class
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
