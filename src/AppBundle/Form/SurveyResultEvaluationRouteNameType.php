<?php

namespace AppBundle\Form;

use AppBundle\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyResultEvaluationRouteNameType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'resultEvaluationRouteName',
            ChoiceType::class,
            [
                'attr' => ['class' => 'js-survey-attribute-form-field'],
                'label' => 'form.label.result_evaluation',
                'choices' => [
                    'form.choices.result_evaluation.custom' => 'custom_result_evaluation',
                    'form.choices.result_evaluation.bunny' => 'bunny_result_evaluation',
                ],
            ]
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
