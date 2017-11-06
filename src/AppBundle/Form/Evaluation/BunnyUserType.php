<?php

namespace AppBundle\Form\Evaluation;

use AppBundle\Entity\BunnyUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BunnyUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('telephoneNumber')
            ->add('companyTitle')
            ->add('street')
            ->add('streetNumber')
            ->add('city')
            ->add('zipCode')
            ->add('isDataProcessingConfirmed');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => false,
                'data_class' => BunnyUser::class,
            ]
        );
    }
}
