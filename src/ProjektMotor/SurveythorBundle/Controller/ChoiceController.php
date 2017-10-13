<?php
namespace PM\SurveythorBundle\Controller;

use PM\SurveythorBundle\Entity\Choice;
use PM\SurveythorBundle\Repository\ChoiceRepository;
use PM\SurveythorBundle\Repository\ConditionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * ChoiceController
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class ChoiceController
{
    /**
     * @var ChoiceRepository
     */
    private $choiceRepository;

    /**
     * @var ConditionRepository
     */
    private $conditionRepository;

    /**
     * @param ChoiceRepository    $choiceRepository
     * @param ConditionRepository $conditionRepository
     */
    public function __construct(
        ChoiceRepository $choiceRepository,
        ConditionRepository $conditionRepository
    ) {
        $this->choiceRepository = $choiceRepository;
        $this->conditionRepository = $conditionRepository;
    }

    /**
     * @param Choice $choice
     *
     * @return JsonResponse
     */
    public function deleteAction(Choice $choice)
    {
        $conditions = $this->conditionRepository->getConditionsByChoice($choice);
        if (empty(($conditions))) {
            $this->choiceRepository->remove($choice);

            return new JsonResponse(
                [
                    'status' => 'OK',
                ]
            );
        } else {
            return new JsonResponse(
                [
                    'status' => 'FAIL',
                    'reason' => 'Diese Antwort kann nicht gelöscht werden, sie wird von mind. einer Bedingung verwendet.',
                ]
            );
        }
    }
}
