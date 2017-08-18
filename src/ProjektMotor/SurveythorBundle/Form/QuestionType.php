<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PM\SurveythorBundle\Entity\Question;
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

        $formModifier = function (FormInterface $form, $type = null) {
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
            function (FormEvent $event) use ($formModifier) {
                $data = $event->getData();

                if (!is_null($data)) {
                    $formModifier($event->getForm(), $data->getType());
                }
            }
        );

        $builder->get('type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $type = $event->getForm()->getData();
                $formModifier($event->getForm()->getParent(), $type);
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
