<?php
namespace PM\SurveythorBundle\Controller;

use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\Question;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\TextAnswer;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Form\ResultType;
use PM\SurveythorBundle\Repository\SurveyRepository;
use QafooLabs\MVC\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher as EventDispatcher;

/**
 * Class ResultController
 * @package PM\SurveythorBundle\Controller
 */
class ResultController
{
    /**
     * @var SurveyRepository
     */
    private $surveyRepository;

    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    /**
     * __construct
     *
     * @param SurveyRepository $surveyRepository
     * @param EventDispatcher $dispatcher
     */
    public function __construct(
        SurveyRepository $surveyRepository,
        EventDispatcher $dispatcher
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param  array $answers
     * @param array|null $choiceIds
     *
     * @return array
     */
    private function getChoiceIdsFromRequest($answers, $choiceIds = null)
    {
        $choiceIds = is_null($choiceIds) ? array() : $choiceIds;

        if (!is_null($answers)) {
            foreach ($answers as $answer) {
                if (array_key_exists('choice', $answer)) {
                    $choiceIds[] = $answer['choice'];
                }
                if (array_key_exists('choices', $answer)) {
                    foreach ($answer['choices'] as $id) {
                        $choiceIds[] = $id;
                    }
                }

                if (array_key_exists('childAnswers', $answer)) {
                    foreach ($answer['childAnswers'] as $childAnswer) {
                        $choiceIds = $this->getChoiceIdsFromRequest([0 => $childAnswer], $choiceIds);
                    }
                }
            }
        }
        return $choiceIds;
    }

    /**
     * @param Result $result
     * @param Answer $newAnswer
     */
    private function addAnswer(Result $result, Answer $newAnswer)
    {
        foreach ($result->getAnswers() as $answer) {
            if ($answer->getQuestion()->getId() == $newAnswer->getQuestion()->getId()) {
                return;
            }
        }
        $result->addAnswer($newAnswer);
        $newAnswer->setPosition($result->getAnswers()->count());
    }

    /**
     * @param Question $question
     * @return MultipleChoiceAnswer|SingleChoiceAnswer|TextAnswer
     * @throws \Exception
     */
    private function answerFactoryMethod(Question $question)
    {
        switch ($question->getType()) {
            case 'mc':
                $answer = new MultipleChoiceAnswer();
                break;
            case 'sc':
                $answer = new SingleChoiceAnswer();
                break;
            case 'text':
                $answer = new TextAnswer();
                break;
            default:
                throw new \Exception('a question has to have a type');
            break;
        }

        $answer->setQuestion($question);
        return $answer;
    }

    /**
     * @param Result $result
     * @param Question $question
     * @param Request $request
     * @param null $answer
     */
    private function setAnswers(
        Result $result,
        Question $question,
        Request $request,
        $answer = null
    ) {
        if (null === $answer) {
            $answer = $this->answerFactoryMethod($question);
            $this->addAnswer($result, $answer);
        }

        if ($question->isChoiceQuestion()) {
            $choiceIds = $this->getChoiceIdsFromRequest($request->request->get('result')['answers']);

            /** @var Choice $choice */
            foreach ($question->getChoices() as $choice) {
                if (in_array($choice->getId(), $choiceIds) && $choice->hasChildQuestions()) {
                    foreach ($choice->getChildQuestions() as $question) {
                        $childAnswer = $this->answerFactoryMethod($question);
                        $answer->addChildAnswer($childAnswer);

                        $this->setAnswers($result, $question, $request, $childAnswer);
                    }
                }
            }
        }
    }

    /**
     * @param FormRequest $formRequest
     * @param Request $request
     * @param Survey $survey
     *
     * @return array|Response
     */
    public function newAction(FormRequest $formRequest, Request $request, Survey $survey)
    {
        $result = new Result();

        foreach ($survey->getQuestions() as $question) {
            $this->setAnswers($result, $question, $request);
        }

        if (!$formRequest->handle(ResultType::class, $result)
            || $request->isXmlHttpRequest()
        ) {
            return array(
                'survey' => $survey,
                'form' => $formRequest->createFormView(),
            );
        }

        $event = new ResultEvent($result);
        $this->dispatcher->dispatch(ResultEvent::NAME, $event);

        return $event->getResponse();
    }
}
