<?php

namespace AppBundle\Form\SurveyItems;

use AppBundle\Entity\Choice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * QuestionChoiceType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class QuestionChoiceType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'text',
                TextType::class,
                [
                    'attr' => ['class' => 'title-field'],
                    'label' => 'Antwort',
                ]
            )
            ->add(
                'value',
                TextType::class,
                [
                    'label' => 'Punkte',
                ]
            )
            ->add(
                'sortOrder',
                HiddenType::class,
                [
                    'attr' => ['class' => 'sortorder'],
                ]
            );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Choice::class,
            )
        );
    }

    public function getBlockPrefix()
    {
        return 'backend_choice';
    }
}
