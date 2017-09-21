<?php
namespace PM\SurveythorBundle\Controller;

use PM\SurveythorBundle\Entity\Answer;
use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Entity\Result;
use PM\SurveythorBundle\Entity\Survey;
use PM\SurveythorBundle\Entity\SurveyItem;
use PM\SurveythorBundle\Entity\SurveyItems\Question;
use PM\SurveythorBundle\Entity\SurveyItems\TextItem;
use PM\SurveythorBundle\Entity\SurveyItems\ItemGroup;
use PM\SurveythorBundle\Entity\ResultItems\SingleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\MultipleChoiceAnswer;
use PM\SurveythorBundle\Entity\ResultItems\TextAnswer;
use PM\SurveythorBundle\Entity\ResultItem;
use PM\SurveythorBundle\Entity\ResultItems\TextItem as ResultTextItem;
use PM\SurveythorBundle\Event\ResultEvent;
use PM\SurveythorBundle\Repository\ResultRepository;
use PM\SurveythorBundle\Form\ResultItemType;
use QafooLabs\MVC\FormRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormFactory;
use Doctrine\ORM\PersistentCollection;

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

    private $resultRepository;


    /**
     * __construct
     *
     * @param SurveyRepository $surveyRepository
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
     * @param Survey $survey
     *
     * @return array|Response
     */
    public function newAction(FormRequest $formRequest, Survey $survey)
    {
        $session = new Session();
        $result = new Result();
        $surveyItem = $survey->getSurveyItems()->first();
        $resultItem = $this->prepareResultItem($surveyItem);

        $session->set('survey', $survey);
        $this->resultRepository->detach($result);
        $session->set('result', $result);


        $formRequest->handle(ResultItemType::class, $resultItem);
        return array(
            'form'  => $formRequest->createFormView(),
            'item'  => $surveyItem
        );
    }

    public function nextAction(FormRequest $formRequest, Survey $survey, SurveyItem $surveyItem)
    {
        $resultItem = $this->prepareResultItem($surveyItem);
        if (!$formRequest->handle(ResultItemType::class, $resultItem)) {
            return array(
                'item'  => $surveyItem,
                'form'  => $formRequest->createFormView()
            );
        }

        $session = new Session();
        $result = $session->get('result');
        $resultItem = $formRequest->getValidData();
        $result->addResultItem($resultItem);
        $session->set('result', $result);

        if ($nextSurveyItem = $this->getNextItem($surveyItem, $survey)) {
            $nextResultItem = $this->prepareResultItem($nextSurveyItem);
            return array(
                'item'  => $nextSurveyItem,
                'form'  => $this->formFactory->create(ResultItemType::class, $nextResultItem)->createView()
            );
        } else {
            $result->setSurvey($survey);
            $result = $this->mergeResult($result);
            $dispatcher = new EventDispatcher();
            $event = new ResultEvent($result);

            $dispatcher->addSubscriber($this->resultReadySubscriber);
            $dispatcher->dispatch(ResultEvent::NAME, $event);

            return new JsonResponse(
                json_encode(
                    array('url' => $event->getUrl())
                )
            );
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

    private function prepareResultItem(SurveyItem $item)
    {
        $session = new Session();
        $result = $session->get('result');

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
