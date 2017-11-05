<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AllowedOrigin;
use AppBundle\Form\AllowedOriginType;
use AppBundle\Repository\AllowedOriginRepository;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;

/**
 * Class AllowedOriginController
 * @package AppBundle\Controller
 */
class AllowedOriginController
{
    private $allowedOriginRepository;

    /**
     * AllowedOriginController constructor.
     *
     * @param AllowedOriginRepository $allowedOriginRepository
     */
    public function __construct(AllowedOriginRepository $allowedOriginRepository)
    {
        $this->allowedOriginRepository = $allowedOriginRepository;
    }

    /**
     * @param FormRequest $formRequest
     *
     * @return array|RedirectRoute
     */
    public function createAction(FormRequest $formRequest)
    {
        if (!$formRequest->handle(AllowedOriginType::class, new AllowedOrigin())) {
            return ['form' => $formRequest->createFormView()];
        }

        /** @var AllowedOrigin $allowedOrigin */
        $allowedOrigin = $formRequest->getValidData();
        $this->allowedOriginRepository->save($allowedOrigin);

        return new RedirectRoute('allowed_origin_list');
    }

    /**
     * @param FormRequest   $formRequest
     * @param AllowedOrigin $allowedOrigin
     *
     * @return array|RedirectRoute
     */
    public function editAction(FormRequest $formRequest, AllowedOrigin $allowedOrigin)
    {
        if (!$formRequest->handle(AllowedOriginType::class, $allowedOrigin)) {
            return [
                'allowedOrigin' => $allowedOrigin,
                'form' => $formRequest->createFormView(),
            ];
        }

        $this->allowedOriginRepository->save($allowedOrigin);

        return new RedirectRoute('allowed_origin_list');
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $allowedOrigins = $this->allowedOriginRepository->findAll();

        return ['allowedOrigins' => $allowedOrigins];
    }

    public function toggleActiveAction(AllowedOrigin $allowedOrigin): array
    {
        $allowedOrigin->setIsActive(!$allowedOrigin->isActive());
        $this->allowedOriginRepository->save($allowedOrigin);

        return ['allowedOrigin' => $allowedOrigin];
    }
}
