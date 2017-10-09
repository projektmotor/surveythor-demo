<?php
namespace PM\SurveythorBundle\Controller;

use AppBundle\Event\ResultReadySubscriber;
use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\ResultItems\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextItem as ResultTextItem;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Form\ResultItemType;
use PM\SurveythorBundle\Repository\ResultRepository;
use QafooLabs\MVC\FormRequest;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @var ResultRepository
     */
    private $resultRepository;


    /**
     * @param EventSubscriberInterface $resultReadySubscriber
     * @param FormFactory              $formFactory
     * @param ResultRepository         $resultRepository
     */
    public function __construct(
        EventSubscriberInterface $resultReadySubscriber,
        FormFactory $formFactory,
        ResultRepository $resultRepository
    ) {
        $this->resultReadySubscriber = $resultReadySubscriber;
        $this->formFactory = $formFactory;
        $this->resultRepository = $resultRepository;
    }

    /**
     * @param FormRequest $formRequest
     * @param Survey      $survey
     *
     * @return array|Response
     */
    public function newAction(FormRequest $formRequest, Survey $survey)
    {
        $result = new Result();
        $surveyItem = $survey->getSurveyItems()->first();
        $resultItem = $this->prepareResultItem($surveyItem);

        $this->resultRepository->save($result);

        $formRequest->handle(ResultItemType::class, $resultItem);

        return array(
            'form'  => $formRequest->createFormView(),
            'item' => $surveyItem,
            'result' => $result,
            'survey' => $survey,
        );
    }

    /**
     * @param FormRequest $formRequest
     * @param Survey      $survey
     * @param SurveyItem  $surveyItem
     * @param Result      $result
     *
     * @return array|JsonResponse
     */
    public function nextAction(FormRequest $formRequest, Survey $survey, SurveyItem $surveyItem, Result $result)
    {
        $resultItem = $this->prepareResultItem($surveyItem);
        if (!$formRequest->handle(ResultItemType::class, $resultItem)) {
            return array(
                'item'  => $surveyItem,
                'result' => $result,
                'survey' => $survey,
                'form'  => $formRequest->createFormView()
            );
        }

        $resultItem = $formRequest->getValidData();
        $result->addResultItem($resultItem);

        if ($nextSurveyItem = $this->getNextItem($surveyItem, $survey)) {
            $nextResultItem = $this->prepareResultItem($nextSurveyItem);

            return array(
                'item' => $nextSurveyItem,
                'result' => $result,
                'survey' => $survey,
                'form'  => $this->formFactory->create(ResultItemType::class, $nextResultItem)->createView()
            );
        } else {
            $result->setSurvey($survey);
            $result = $this->mergeResult($result);
            $dispatcher = new EventDispatcher();
            $event = new ResultEvent($result);

            $dispatcher->addSubscriber($this->resultReadySubscriber);
            $dispatcher->dispatch(ResultEvent::NAME, $event);

            $jsonResponse = new JsonResponse(
                json_encode(
                    array('url' => $event->getUrl())
                ),
                200,
                [
                    'Access-Control-Allow-Origin' => 'http://surveythor-demo',
                ]
            );

            return $jsonResponse;
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
            foreach ($result->getResultItems() as $resultItem) {
                if ($answer = $resultItem->getSingleChoiceAnswer()) {
                    $resultChoices[] = $answer->getChoice()->getId();
                }
                if ($answer = $resultItem->getMultipleChoiceAnswer()) {
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

    /**
     * @param SurveyItem $item
     *
     * @return ResultItem
     */
    private function prepareResultItem(SurveyItem $item)
    {
        if ($item instanceof Question) {
            $resultItem = $this->prepareAnswer($item);
        }

        if ($item instanceof TextItem) {
            $resultItem = new ResultItem();

            $textItem = new ResultTextItem();
            $textItem->setText($item->getText());
            $resultItem->setTextItem($textItem);
        }

        if ($item instanceof ItemGroup) {
            $resultItem = new ResultItem();
            $childItems = $item->getSurveyItems();
            $childItem = $childItems->current();
            if ($this->isItemVisible($childItem)) {
                $resultItem = new ResultItem();
                $resultItem->addChildItem($this->prepareResultItem($childItem));
            }
            while ($childItem = $childItems->next()) {
                if ($this->isItemVisible($childItem)) {
                    $resultItem->addChildItem(
                        $this->prepareResultItem($childItem)
                    );
                }
            }
        }

        $resultItem->setSurveyItem($item);

        return $resultItem;
    }

    private function prepareAnswer(SurveyItem $item)
    {
        $resultItem = new ResultItem();
        $answer = Answer::createByQuestionType($item);
        $answer->setQuestion($item);

        if ($answer instanceof MultipleChoiceAnswer) {
            $resultItem->setMultipleChoiceAnswer($answer);
        }
        if ($answer instanceof SingleChoiceAnswer) {
            $resultItem->setSingleChoiceAnswer($answer);
        }
        if ($answer instanceof TextAnswer) {
            $resultItem->setTextAnswer($answer);
        }

        return $resultItem;
    }

    private function mergeResult($result)
    {
        foreach ($result->getResultItems() as $resultItem) {
            $resultItem = $this->mergeResultItem($resultItem);
        }

        return $result;
    }

    private function mergeResultItem($resultItem, $recursive = false)
    {
        $surveyItem = $resultItem->getSurveyItem();
        $surveyItem = $this->resultRepository->merge($surveyItem);
        $resultItem->setSurveyItem($surveyItem);

        if ($answer = $resultItem->getSingleChoiceAnswer()) {
            $question = $answer->getQuestion();
            $question = $this->resultRepository->merge($question);

            $choice = $answer->getChoice();
            $choice = $this->resultRepository->merge($choice);

            $answer->setChoice($choice);
            $answer->setQuestion($question);
        }

        if ($answer = $resultItem->getMultipleChoiceAnswer()) {
            $question = $answer->getQuestion();
            $question = $this->resultRepository->merge($question);

            $choices = $answer->getChoices();
            $answer->clearChoices();

            foreach ($choices as $choice) {
                $choice = $this->resultRepository->merge($choice);
                $answer->addChoice($choice);
            }
            $answer->setQuestion($question);
        }

        if ($answer = $resultItem->getTextAnswer()) {
            $question = $answer->getQuestion();
            $question = $this->resultRepository->merge($question);
            $answer->setQuestion($question);
        }

        if ($resultItem->hasChildren() && $recursive === false) {
            $this->mergeChildren($resultItem);
        }

        return $resultItem;
    }

    private function mergeChildren($resultItem)
    {
        $childItems = $resultItem->getChildItems();
        foreach ($childItems as $childItem) {
            $this->mergeChildren($childItem);
        }
        $this->mergeResultItem($resultItem, true);
    }
}
