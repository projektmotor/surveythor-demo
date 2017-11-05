<?php

namespace AppBundle\Form;

use AppBundle\Entity\AllowedOrigin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AllowedOriginType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('originName', TextType::class, ['attr' => ['placeholder' => 'allowed_origin.form.example']]);
        $builder->add('description');
        $builder->add('isActive');
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => AllowedOrigin::class,
            ]
        );
    }
}

