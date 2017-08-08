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

        $menu->addChild('Umfragen', ['route' => 'survey_index']);

        return $menu;
    }
}
