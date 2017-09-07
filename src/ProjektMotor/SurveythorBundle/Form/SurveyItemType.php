<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\PersistentCollection;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Form\SurveyItems\QuestionType;
use PM\SurveythorBundle\Form\SurveyItems\TextItemType;

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
                'label' => 'Titel'
            ))
            ->add('displayTitle', null, array(
                'label' => 'Titel anzeigen'
            ))
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $item = $event->getData();

                if (!is_null($item)) {
                    $form = $event->getForm();
                    $itemClass = \Doctrine\Common\Util\ClassUtils::getRealClass(get_class($item->getContent()));
                    switch ($itemClass) {
                        case Question::class:
                            $form->add('question', QuestionType::class, array(
                                'label' => false
                            ));
                            break;

                        case TextItem::class:
                            $form->add('textItem', TextItemType::class, array(
                                'label' => false
                            ));
                            break;

                        case PersistentCollection::class:
                            $form->add('childItems', CollectionType::class, array(
                                'entry_type' => SurveyItemType::class
                            ));
                            break;

                        default:
                            var_dump($itemClass);
                            die();
                            break;
                    }
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
                        'entry_type' => CondtionType::class,
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
