<?php

namespace AppBundle\Form;

use AppBundle\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SurveyTitleType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyTitleType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Name der Umfrage'])
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'Beschreibung',
                    'required' => false,
                ]
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $survey = $event->getData();
                $form = $event->getForm();

                if (is_null($survey)) {
                    $form->add('submit', SubmitType::class);
                } else {
                    $form->remove('title');
                    $form->remove('description');
                    $form
                        ->add(
                            'title',
                            TextType::class,
                            [
                                'label' => 'Name der Umfrage',
                                'attr' => ['class' => 'survey-title-field'],
                            ]
                        )
                        ->add(
                            'description',
                            TextareaType::class,
                            [
                                'label' => 'Beschreibung',
                                'required' => false,
                                'attr' => ['class' => 'survey-title-field'],
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
                'data_class' => Survey::class,
            ]
        );
    }
}
