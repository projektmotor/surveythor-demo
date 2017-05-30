<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PM\SurveythorBundle\Entity\Dto\Survey;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * SurveyType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_backend_survey';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('questions', CollectionType::class, array(
                'entry_type' => QuestionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => true
            ))
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Survey::class
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
