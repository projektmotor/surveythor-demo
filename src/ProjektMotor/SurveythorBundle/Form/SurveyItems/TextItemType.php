<?php
namespace PM\SurveythorBundle\Form\SurveyItems;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;

/**
 * TextItemType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class TextItemType extends AbstractType
{
    const FORM_NAME = '';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text');
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TextItem::class
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
