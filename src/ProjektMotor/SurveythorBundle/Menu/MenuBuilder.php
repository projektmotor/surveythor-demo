<?php

namespace PM\SurveythorBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * MenuBuilder
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @param FactoryInterface              $factory
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(FactoryInterface $factory, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->factory = $factory;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem(
            'root',
            [
                'childrenAttributes' => [
                    'class' => 'nav navbar-nav',
                ],
            ]
        );

        if ($this->authorizationChecker->isGranted('ROLE_EDITOR')) {
            $menu->addChild('menu.surveys', ['route' => 'survey_index']);
        }
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('menu.users', ['route' => 'user_list']);
            $menu->addChild('menu.allowed_origins', ['route' => 'allowed_origin_list']);
        }

        return $menu;
    }
}
