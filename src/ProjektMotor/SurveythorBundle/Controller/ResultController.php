<?php
namespace PM\SurveythorBundle\Controller;

use QafooLabs\MVC\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher as EventDispatcher;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\ResultAnswer;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Question;
use PM\SurveythorBundle\Repository\SurveyRepository;
use PM\SurveythorBundle\Form\ResultType;
use PM\SurveythorBundle\Form\ResultAnswerType;
use PM\SurveythorBundle\Event\ResultEvent;

/**
 * ResultController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ResultController
{
    /**
     * @var SurveyRepository
     */
    private $surveyRepository;

    private $dispatcher;

    /**
     * __construct
     *
     * @param SurveyRepository $surveyRepository
     */
    public function __construct(
        SurveyRepository $surveyRepository,
        EventDispatcher $dispatcher
    ) {
        $this->surveyRepository = $surveyRepository;
        $this->dispatcher = $dispatcher;
    }

    private function getAnswerIdsFromRequest($resultAnswers, $answerIds = null)
    {
        $answerIds = is_null($answerIds) ? array() : $answerIds;

        if (!is_null($resultAnswers)) {
            foreach ($resultAnswers as $resultAnswer) {
                if (array_key_exists('answer', $resultAnswer)) {
                    $answerIds[] = $resultAnswer['answer'];
                }
                if (array_key_exists('answers', $resultAnswer)) {
                    foreach ($resultAnswer['answers'] as $answerId) {
                        $answerIds[] = $answerId;
                    }
                }

                if (array_key_exists('childAnswers', $resultAnswer)) {
                    foreach ($resultAnswer['childAnswers'] as $childAnswer) {
                        $answerIds = $this->getAnswerIdsFromRequest([0 => $childAnswer], $answerIds);
                    }
                }
            }
        }
        return $answerIds;
    }

    private function addResultAnswer($result, $newResultAnswer)
    {
        foreach ($result->getResultAnswers() as $resultAnswer) {
            if ($resultAnswer->getQuestion()->getId() == $newResultAnswer->getQuestion()->getId()) {
                return;
            }
        }
        $result->addResultAnswer($newResultAnswer);
    }

    private function setResultAnswers(
        Result $result,
        Question $question,
        Request $request,
        $resultAnswer = null
    ) {
        if (null === $resultAnswer) {
            $resultAnswer = new ResultAnswer();
            $resultAnswer->setQuestion($question);
            $this->addResultAnswer($result, $resultAnswer);
        }

        if ($question->getType() == 'mc' || $question->getType() == 'sc') {
            foreach ($question->getAnswers() as $answer) {
                if (in_array($answer->getId(), $this->getAnswerIdsFromRequest($request->request->get('result')['resultAnswers']))
                    && !is_null($answer->getChildQuestions())
                ) {
                    foreach ($answer->getChildQuestions() as $question) {
                        $childAnswer = new ResultAnswer();
                        $childAnswer->setQuestion($question);
                        $resultAnswer->addChildAnswer($childAnswer);

                        $this->setResultAnswers($result, $question, $request, $childAnswer);
                    }
                }
            }
        }
    }

    public function newAction(FormRequest $formRequest, Request $request, Survey $survey)
    {
        $result = new Result();

        foreach ($survey->getQuestions() as $question) {
            $this->setResultAnswers($result, $question, $request);
        }

        if (!$formRequest->handle(ResultType::class, $result)
            || $request->isXmlHttpRequest()
        ) {
            return array(
                'survey' => $survey,
                'form' => $formRequest->createFormView()
            );
        }

        $event = new ResultEvent($result);
        $this->dispatcher->dispatch(ResultEvent::NAME, $event);

        return $event->getResponse();
    }
}
