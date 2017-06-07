<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PM\SurveythorBundle\Entity\ResultAnswer;
use PM\SurveythorBundle\Entity\Answer;

/**
 * ResultAnswerType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultAnswerType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_result_answer';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', null, ['label' => false ])
            ->add('answer', EntityType::class, array(
                'label' => false,
                'class' => Answer::class,
                'choice_label' => 'text'
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ResultAnswer::class
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
