<?php
namespace PM\SurveythorBundle\Form\ResultItems;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PM\SurveythorBundle\Entity\ResultItems\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\Choice;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

/**
 * SingleChoiceAnswerType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class SingleChoiceAnswerType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_singlechoiceanswer';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $answer = $event->getData();
            $form = $event->getForm();

            if ($answer) {
                $question = $answer->getQuestion();

                $type = EntityType::class;
                if (!is_null($question->getTemplate())) {
                    $type = !is_null($question->getTemplate()->getFormType())
                        ? $question->getTemplate()->getFormType()
                        : $type
                    ;
                }

                $form->add('choice', $type, array(
                    'label' => false,
                    'class' => Choice::class,
                    'choice_label' => 'text',
                    'choices' => $question->getChoices(),
                    'expanded' => true,
                    'attr' => array(
                        'class' => 'choice-answer'
                    ),
                    'choice_attr' => function ($val, $key, $index) {
                        return array(
                            'data-answer-id' => $val->getId()
                        );
                    }
                ));
            }
        });
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'question' => null,
            'data_class' => SingleChoiceAnswer::class
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
