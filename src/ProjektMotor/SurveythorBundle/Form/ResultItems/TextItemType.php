<?php
namespace PM\SurveythorBundle\Form\ResultItems;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PM\SurveythorBundle\Entity\ResultItems\TextItem;

/**
 * TextItemType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class TextItemType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_textitem';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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

    public function getBlockPrefix()
    {
        return self::FORM_NAME;
    }
}
