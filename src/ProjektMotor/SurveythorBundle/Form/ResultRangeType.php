<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use PM\SurveythorBundle\Entity\ResultRange;

/**
 * ResultRangeType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultRangeType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_resultrange';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('meaning', TextareaType::class, array(
                'label' => 'Beschreibung (wird nicht angezeigt)'
            ))
            ->add('start', NumberType::class, array(
                'label' => 'minimale Punktzahl'
            ))
            ->add('stop', NumberType::class, array(
                'label' => 'maximale Punktzahl'
            ))
            ->add('event', ChoiceType::class, array(
                'choices' => array(
                    'Redirect zu Webseite' => 0,
                    'Text anzeigen' => 1
                ),
                'label' => 'Aktion'
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ResultRange::class
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
