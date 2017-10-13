<?php
namespace AppBundle\DependencyInjection;

use AppBundle\Event\ResultReadySubscriber;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

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
