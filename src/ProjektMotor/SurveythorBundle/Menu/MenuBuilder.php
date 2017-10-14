<?php
namespace PM\SurveythorBundle\Menu;

use Knp\Menu\FactoryInterface;

/**
 * MenuBuilder
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
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

        $menu->addChild('menu.surveys', ['route' => 'survey_index']);
        $menu->addChild('menu.users', ['route' => 'user_index']);
        $menu->addChild('menu.allowed_origins', ['route' => 'allowed_origin_list']);

        return $menu;
    }
}
