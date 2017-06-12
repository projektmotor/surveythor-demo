<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use PM\SurveythorBundle\Entity\Dto\Question;
use PM\SurveythorBundle\Entity\Question;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

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
                    'multiple choice question' => 'mc'
                ),
                'attr' => array(
                    'class' => 'question-type-select'
                ),
                'placeholder' => ''
            ))
            ->add('text', HiddenType::class)
        ;

        $formModifier = function (FormInterface $form, $type = null) {
            if (null !== $type) {
                $form->add('text', TextType::class, array(
                        'attr' => array('class' => 'title-field')
                ));

                if ($type == 'mc') {
                    $form->add('answers', AnswerCollectionType::class, array(
                        'entry_type' => AnswerMultipleChoiceType::class,
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                        'entry_options' => array(
                            'label' => false
                        ),
                        'prototype_name' => '__answer__',
                        'attr' => array('class' => 'question-answer-prototype')
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
