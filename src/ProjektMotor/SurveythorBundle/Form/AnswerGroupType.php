<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

/**
 * AnswerGroupType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class AnswerGroupType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_result_answergroup';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('answers', CollectionType::class, array(
                'entry_type' => AnswerType::class,
                'label' => false,
                'by_reference' => false,
                'entry_options' => array(
                    'label' => false
                )
            ))
            ->add('childGroups', CollectionType::class, array(
                'entry_type' => AnswerGroupType::class,
                'label' => false,
                'by_reference' => false,
                'entry_options' => array(
                    'label' => false
                )
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\SurveythorBundle\Entity\AnswerGroup'
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
