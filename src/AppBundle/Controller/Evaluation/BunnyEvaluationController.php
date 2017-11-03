<?php

namespace AppBundle\Controller\Evaluation;

use AppBundle\Entity\BunnyUser;
use AppBundle\Entity\Result;
use AppBundle\Form\Evaluation\BunnyUserType;
use AppBundle\Repository\BunnyUserRepository;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;

class BunnyEvaluationController
{
    private $bunnyUserRepository;

    public function __construct(BunnyUserRepository $bunnyUserRepository)
    {
        $this->bunnyUserRepository = $bunnyUserRepository;
    }

    /**
     * @param FormRequest $formRequest
     * @param Result      $result
     *
     * @return array|RedirectRoute
     */
    public function evaluateResultAction(FormRequest $formRequest, Result $result)
    {
        $bunnyUser = BunnyUser::createByResult($result);
        if (!$formRequest->handle(BunnyUserType::class, $bunnyUser)) {
            return [
                'bunnyUserForm' => $formRequest->createFormView(),
                'result' => $result,
            ];
        }

        $survey = $formRequest->getValidData();
        $this->bunnyUserRepository->save($survey);

        return new RedirectRoute(
            'bunny_result_evaluation_finished', ['result' => $result->getId()]
        );
    }

    public function evaluationFinishedAction(Result $result): array
    {
        return [
            'result' => $result,
        ];
    }
}
