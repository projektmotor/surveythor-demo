<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use PM\SurveythorBundle\Entity\Condition;
use PM\SurveythorBundle\Entity\Question;
use PM\SurveythorBundle\Entity\Choice;

/**
 * QuestionCondtionType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class CondtionType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_questioncondition';

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $survey = $options['question']->getSurvey();

        $builder->add('question', EntityType::class, array(
            'class' => Question::class,
            'choices' => $survey->getQuestions(),
            'choice_label' => 'text',
            'attr' => array('class' => 'condition-question')
        ));

        $questionModifier = function (FormInterface $form, $question) {
            if (!is_null($question)) {
                if ($question->hasChoices()) {
                    $form->add('choices', EntityType::class, array(
                        'class' => Choice::class,
                        'required' => false,
                        'choice_label' => 'text',
                        'choices' => $question->getChoices(),
                        'expanded' => true,
                        'multiple' => true
                    ));
                }
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($questionModifier) {
                $data = $event->getData();

                if (!is_null($data)) {
                    $question = $data->getQuestion();
                    $questionModifier($event->getForm(), $question);
                }
            }
        );

        $builder->get('question')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($questionModifier) {
                $question = $event->getForm()->getData();
                $questionModifier($event->getForm()->getParent(), $question);
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\SurveythorBundle\Entity\Condition',
            'question' => null
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
