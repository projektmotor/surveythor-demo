<?php
namespace PM\SurveythorBundle\Form\SurveyItems;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\PersistentCollection;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Form\SurveyItems\QuestionType;
use PM\SurveythorBundle\Form\SurveyItems\TextItemType;
use PM\SurveythorBundle\Form\Event\SurveyItemsCollectionSubscriber;

/**
 * SurveyItemCollectionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SurveyItemCollectionType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_surveyitemcollection';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('title');
        $builder->add('displayTitle');
        #$builder->addEventSubscriber(
        #    new SurveyItemsCollectionSubscriber(
        #        $builder->getFormFactory()
        #    )
        #);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ItemGroup::class
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
