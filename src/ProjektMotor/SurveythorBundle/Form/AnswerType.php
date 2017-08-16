<?php
namespace PM\SurveythorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\Question;
use PM\SurveythorBundle\Form\Result\ChoicesHorizontalType;

/**
 * AnswerType
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class AnswerType extends AbstractType
{
    const FORM_NAME = 'pm_surveythor_result_answer';

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

                switch ($question->getType()) {
                    case 'sc':
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
                                $parentChoice = !is_null($val->getQuestion()->getParentChoice())
                                    ? $val->getQuestion()->getParentChoice()->getId()
                                    : null;

                                return array(
                                    'data-answer-id' => $val->getId()
                                );
                            }
                        ));
                        break;
                    case 'mc':
                        $form->add('choices', $type, array(
                            'label' => false,
                            'class' => Choice::class,
                            'choice_label' => 'text',
                            'choices' => $question->getChoices(),
                            'expanded' => true,
                            'multiple' => true,
                            'attr' => array(
                                'class' => 'choice-answer'
                            ),
                            'choice_attr' => function ($val, $key, $index) {
                                $parentChoice = !is_null($val->getQuestion()->getParentChoice())
                                    ? $val->getQuestion()->getParentChoice()->getId()
                                    : null;

                                return array(
                                    'data-answer-id' => $val->getId()
                                );
                            }
                        ));
                        break;
                    default:
                        $form->add('value', TextType::class, array(
                            'label' => false
                        ));
                        break;
                }

                $type = AnswerCollectionType::class;
                $type = $question->getChildrenTemplate() !== null
                    ? $question->getChildrenTemplate()->getFormType()
                    : $type
                ;
                if (!is_null($answer->getChildAnswers())) {
                    foreach ($answer->getChildAnswers() as $childAnswer) {
                        $form->add('childAnswers', $type, array(
                            'entry_type' => AnswerType::class,
                            'label' => false,
                            'by_reference' => true,
                            'entry_options' => array(
                                'label' => false,
                                'attr' => array(
                                )
                            ),
                            'attr' => array(
                                'class' => 'question-childanswer'
                            ),
                        ));
                    }
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
            'data_class' => Answer::class
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
