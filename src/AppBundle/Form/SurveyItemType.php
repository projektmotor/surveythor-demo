<?php

namespace AppBundle\Form;

use AppBundle\Entity\QuestionTemplate;
use AppBundle\Entity\SurveyItem;
use AppBundle\Entity\SurveyItems\ItemGroup;
use AppBundle\Entity\SurveyItems\Question;
use AppBundle\Entity\SurveyItems\SurveyTextItem;
use AppBundle\Form\SurveyItems\ChoiceCollectionType;
use AppBundle\Form\SurveyItems\QuestionChoiceType;
use AppBundle\Form\SurveyItems\SurveyItemCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Titel',
                'attr' => array('class' => 'surveyitem-title')
            ))
            ->add('displayTitle', null, array(
                'label' => 'Titel anzeigen'
            ))
            ->add('description', null, array(
                'label' => 'Beschreibung'
            ))
            ->add('sortOrder', HiddenType::class, [ 'label' => false, 'attr' => [ 'class' => 'sortorder'] ])
            ->add('type', HiddenType::class, [ 'label' => false, 'mapped' => false ]);
        #$builder->addEventListener(
        #    FormEvents::POST_SUBMIT,
        #    function (FormEvent $event) {
        #        $form = $event->getForm();
        #        $form->remove('type');
        #        $form->remove('id');
        #    }
        #);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $item = $event->getData();

                if (!is_null($item)) {
                    $form = $event->getForm();
                    $itemClass = \Doctrine\Common\Util\ClassUtils::getRealClass(get_class($item));
                    switch ($itemClass) {
                        case Question::class:
                            $questionType = $item->getType();

                            $form->add('text', null, array(
                                'attr' => array('class' => 'surveyitem-text')
                            ));
                            if ($questionType == 'mc' || $questionType == 'sc') {
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
                                    'attr' => array('class' => 'question-answer-prototype')
                                ));
                                $form->add('template', EntityType::class, array(
                                    'class' => QuestionTemplate::class,
                                    'required' => false,
                                    'choice_label' => 'name',
                                    'label' => 'Choices Layout'
                                ));
                            }
                            switch ($questionType) {
                                case 'sc':
                                    $type = 'singleChoice';
                                    break;
                                case 'mc':
                                    $type = 'multipleChoice';
                                    break;

                                case 'text':
                                    $type = 'textQuestion';
                                    break;
                            }

                            $form->remove('type');
                            $form->add('type', HiddenType::class, array(
                                'label' => false,
                                'data' => $type,
                                'attr' => array('class' => 'surveyitem-type'),
                                'mapped' => false
                            ));
                            break;

                        case SurveyTextItem::class:
                            $form->add('text', null, array(
                                'attr' => array('class' => 'surveyitem-text')
                            ));
                            $form->remove('type');
                            $form->add('type', HiddenType::class, array(
                                'label' => false,
                                'data' => 'textItem',
                                'attr' => array('class' => 'surveyitem-type'),
                                'mapped' => false
                            ));
                            break;

                        case ItemGroup::class:
                            $form->add('surveyItems', SurveyItemCollectionType::class, array(
                                'entry_type' => SurveyItemType::class,
                                'entry_options' => array('label' => false),
                                'allow_add' => true,
                                'label' => false,
                                'attr' => array('class' => 'sortable-itemgroup draggable-connect')
                            ));
                            $form->remove('type');
                            $form->add('type', HiddenType::class, array(
                                'label' => false,
                                'data' => 'itemGroup',
                                'attr' => array('class' => 'surveyitem-type'),
                                'mapped' => false
                            ));
                            break;

                        default:
                            var_dump($itemClass);
                            die();
                            break;
                    }

                    //if (is_null($item->getItemGroup())) {
                    //    $form->add('submit', SubmitType::class, array(
                    //        'label' => 'Speichern',
                    //        'attr' => array( 'class' => 'surveyitem-submit btn-default')
                    //    ));
                    //}
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $surveyItem = $event->getData();
                if (!is_null($surveyItem)) {
                    $form = $event->getForm();
                    $form->add('conditions', CollectionType::class, array(
                        'entry_type' => ConditionType::class,
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => true,
                        'entry_options' => array(
                            'item' => $surveyItem,
                            'label' => false
                        ),
                        'label' => 'Bedingungen',
                        'prototype_name' => '__condition__'
                    ));
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
            'data_class' => SurveyItem::class,
            'empty_data' => function ($form) {
                switch ($form->getExtraData()['type']) {
                    case 'qestion':
                        return new Question();
                        break;
                    case 'textitem':
                        return new SurveyTextItem();
                        break;
                    case 'itemgroup':
                        return new ItemGroup();
                        break;
                }
            }
        ));
    }

    public function getBlockPrefix()
    {
        return 'surveyitem';
    }
}
