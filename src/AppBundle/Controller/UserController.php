<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use FOS\UserBundle\Model\UserManagerInterface;
use QafooLabs\MVC\FormRequest;
use QafooLabs\MVC\RedirectRoute;

/**
 * @package AppBundle\Controller
 */
class UserController
{
    private $userManager;

    /**
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param FormRequest $formRequest
     *
     * @return array|RedirectRoute
     */
    public function createAction(FormRequest $formRequest)
    {
        if (!$formRequest->handle(UserType::class, new User())) {
            return ['form' => $formRequest->createFormView()];
        }

        /** @var User $user */
        $user = $formRequest->getValidData();
        $this->userManager->updateUser($user);

        return new RedirectRoute('user_list');
    }

    /**
     * @param FormRequest $formRequest
     * @param User        $user
     *
     * @return array|RedirectRoute
     */
    public function editAction(FormRequest $formRequest, User $user)
    {
        if (!$formRequest->handle(UserType::class, $user)) {
            return [
                'user' => $user,
                'form' => $formRequest->createFormView(),
            ];
        }

        $this->userManager->updateUser($user);

        return new RedirectRoute('user_list');
    }

    /**
     * @return array
     */
    public function listAction()
    {
        $users = $this->userManager->findUsers();

        return ['users' => $users];
    }
}
