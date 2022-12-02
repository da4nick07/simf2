<?php

namespace App;

use App\Service\SpamChecker;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use App\CompilerPass\TestPass;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    // не работает...
    // что не так - не пойму пока...
/*
    public function process(ContainerBuilder $container): void
    {
        // in this method you can manipulate the service container:
        // for example, changing some container service:
        $container->getDefinition(SpamChecker::class)->setPublic(true);

        // or processing tagged services:
       foreach ($container->findTaggedServiceIds('test.services') as $id => $tags) {
           $container->getDefinition($id)->setPublic(true);
        }
    }
*/

    protected function build(ContainerBuilder $container): void
    {
//        $container->addCompilerPass(new TestPass());
    }

}
