<?php
namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use AppBundle\Event\ResultReadySubscriber;
use PM\SurveythorBundle\Event\ResultEvent;

/**
 * ResultReadyPass
 * @author Rombo Kraft <kraft@projektmotor.de>
 */
class OverrideResultReadyPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $containerBuilder)
    {
        $definition = $containerBuilder->getDefinition('result_ready_subscriber');
        $definition->setClass(ResultReadySubscriber::class);
        $definition->addArgument(new Reference('router'));
    }
}
