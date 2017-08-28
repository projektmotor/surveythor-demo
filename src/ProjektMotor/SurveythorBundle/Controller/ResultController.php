<?php
namespace PM\SurveythorBundle\Controller;

use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\AnswerGroup;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\Question;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\TextItem;
use PM\SurveythorBundle\Entity\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\QuestionGroup;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Form\ResultType;
use PM\SurveythorBundle\Form\AnswerType;
use PM\SurveythorBundle\Form\AnswerGroupType;
use PM\SurveythorBundle\Repository\SurveyRepository;
use QafooLabs\MVC\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormFactory;

/**
 * Class ResultController
 * @package PM\SurveythorBundle\Controller
 */
class ResultController
{
    /**
     * @var ResultReadySubscriber
     */
    private $resultReadySubscriber;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * __construct
     *
     * @param SurveyRepository $surveyRepository
     */
    public function __construct(
        EventSubscriberInterface $resultReadySubscriber,
        FormFactory $formFactory
    ) {
        $this->resultReadySubscriber = $resultReadySubscriber;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Survey $survey
     *
     * @return array|Response
     */
    public function newAction(Survey $survey)
    {
        $session = new Session();
        $result = new Result();
        $item = $survey->getSurveyItems()->first();

        $session->set('result', $result);
        $session->set('survey', $survey);

        return array('item' => $item, $survey);
    }

    public function nextAction(FormRequest $formRequest, Survey $survey, SurveyItem $item)
    {
        $session = new Session();

        if (get_class($item) == Question::class) {
            $answer = Answer::createByQuestionType($item);
            if (!$formRequest->handle(AnswerType::class, $answer)) {
                return $this->renderItem($item, $survey, $formRequest);
            }
            $session->get('result')->addAnswer($formRequest->getValidData());
        }
        if (get_class($item) == QuestionGroup::class) {
            $answerGroup = new AnswerGroup();

            foreach ($item->getQuestions() as $question) {
                $answer = Answer::createByQuestionType($question);
                $answerGroup->addAnswer($answer);
            }

            if ($item->getChildGroups()->count() > 0) {
                foreach ($item->getChildGroups() as $group) {
                    if ($this->isItemVisible($group)) {
                        $childGroup = new AnswerGroup();
                        foreach ($group->getQuestions() as $question) {
                            $answer = Answer::createByQuestionType($question);
                            $childGroup->addAnswer($answer);
                            $childGroup->setHeader($group->getHeader());
                        }
                        $answerGroup->addChildGroup($childGroup);
                    }
                }
            }

            if (!$formRequest->handle(AnswerGroupType::class, $answerGroup)) {
                return $this->renderItem($item, $survey, $formRequest);
            }
            $session->get('result')->addAnswerGroup($formRequest->getValidData());
        }

        if ($nextItem = $this->getNextItem($item, $survey)) {
            return $this->renderItem($nextItem, $survey);
        } else {
            dump($session->get('result'));
            die();
        }
    }

    private function getNextItem(SurveyItem $item, Survey $survey)
    {
        if ($nextItem = $survey->getNextItem($item)) {
            if ($this->isItemVisible($nextItem)) {
                return $nextItem;
            }
            return $this->getNextItem($nextItem, $survey);
        }
        return false;
    }

    private function isItemVisible(SurveyItem $item, $condition = null, $visible = null)
    {
        $session = new Session();
        $result = $session->get('result');

        $visible = true;
        if (0 != sizeof($item->getConditions())) {
            $resultChoices = array();
            foreach ($result->getAnswers() as $answer) {
                if ($answer instanceof SingleChoiceAnswer) {
                    $resultChoices[] = $answer->getChoice()->getId();
                }
                if ($answer instanceof MultipleChoiceAnswer) {
                    foreach ($answer->getChoices() as $choice) {
                        $resultChoices[] = $choice->getId();
                    }
                }
            }

            foreach ($item->getConditions() as $condition) {
                foreach ($condition->getChoices() as $choice) {
                    if (false === $condition->getIsNegative()) {
                        $visible = !in_array($choice->getId(), $resultChoices) ? false : true;
                        if (true === $visible) {
                            continue 2;
                        }

                    } else {
                        $visible = in_array($choice->getId(), $resultChoices) ? false : $visible;
                    }
                }
                if (false === $visible) {
                    return false;
                }
            }
        }
        return $visible;
    }

    private function renderItem(SurveyItem $item, Survey $survey, FormRequest $formRequest = null)
    {
        switch (get_class($item)) {
            case Question::class:
                $answer = Answer::createByQuestionType($item);
                if (null === $formRequest) {
                    $form = $this->formFactory->create(AnswerType::class, $answer)->createView();
                } else {
                    $form = $formRequest->createFormView();
                }
                return array(
                    'item' => $item,
                    'form' => $form
                );
            break;
            case QuestionGroup::class:
                $answerGroup = new AnswerGroup();
                foreach ($item->getQuestions() as $question) {
                    $answer = Answer::createByQuestionType($question);
                    $answerGroup->addAnswer($answer);
                }
                if ($item->getChildGroups()->count() > 0) {
                    foreach ($item->getChildGroups() as $group) {
                        if ($this->isItemVisible($group)) {
                            $childGroup = new AnswerGroup();
                            foreach ($group->getQuestions() as $question) {
                                $answer = Answer::createByQuestionType($question);
                                $childGroup->addAnswer($answer);
                                $childGroup->setHeader($group->getHeader());
                            }
                            $answerGroup->addChildGroup($childGroup);
                        }
                    }
                }

                if (null === $formRequest) {
                    $form = $this->formFactory->create(AnswerGroupType::class, $answerGroup)->createView();
                } else {
                    $form = $formRequest->createFormView();
                }

                return array(
                    'item' => $item,
                    'formgroup' => $form,
                    'header' => $item->getHeader()
                );
            break;
            case TextItem::class:
                $text = $item->getText();

                return array(
                    'item' => $item,
                    'text' => $text
                );
            break;
        }
    }

//        //dump($request->request->all());die();
//        $result = new Result();
//
//        foreach ($survey->getSurveyItems() as $item) {
//            if ($item instanceof Question) {
//                $this->setAnswers($result, $item, $request);
//            }
//        }
//
//        if (!$formRequest->handle(ResultType::class, $result)
//            || $request->isXmlHttpRequest()
//        ) {
//            return array(
//                'survey' => $survey,
//                'form' => $formRequest->createFormView(),
//            );
//        }
//
//        $dispatcher = new EventDispatcher();
//        $event = new ResultEvent($result);
//
//        $dispatcher->addSubscriber($this->resultReadySubscriber);
//        $dispatcher->dispatch(ResultEvent::NAME, $event);
//
//        return $event->getResponse();
//    }
}
