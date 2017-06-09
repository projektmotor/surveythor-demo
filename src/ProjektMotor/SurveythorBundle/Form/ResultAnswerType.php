<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PM\SurveythorBundle\Entity\ResultAnswer;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Question;

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
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $resultAnswer = $event->getData();
            $form = $event->getForm();
            if ($resultAnswer) {
                $question = $resultAnswer->getQuestion();

                switch ($question->getType()) {
                    case 'mc':
                        $form->add('answer', EntityType::class, array(
                            'label' => false,
                            'class' => Answer::class,
                            'choice_label' => 'text',
                            'choices' => $question->getAnswers(),
                            'expanded' => true,
                            'attr' => array(
                                'class' => 'multiple-choice-answers'
                            )
                        ));
                        break;
                    default:
                        $form->add('value', null, ['label' => false]);
                        break;
                }
            }

            if (!is_null($resultAnswer->getChildAnswers())) {
                foreach ($resultAnswer->getChildAnswers() as $childAnswer) {
                    $form->add('childAnswers', ResultAnswerCollectionType::class, array(
                        'entry_type' => ResultAnswerType::class,
                        'label' => false,
                        'by_reference' => false,
                        'entry_options' => array(
                            'label' => false
                        ),
                        'attr' => array('class' => 'result-childanswer-prototype'),
                    ));
                }
            }
        });

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
