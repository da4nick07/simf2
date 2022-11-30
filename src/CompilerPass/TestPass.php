<?php
namespace App\CompilerPass;

use App\Service\SpamChecker;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TestPass  implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
//        if (! $this->isPHPUnit()) {
//            return;
//        }

        // так, наверное, правильнее
        // если не хочется всё писать в config/services_test.yaml
        if ( $_ENV['APP_ENV'] ==='test') {

            // если "в лоб"
//          $container->getDefinition(SpamChecker::class)->setPublic(true);

            // по тегу
            foreach ($container->findTaggedServiceIds('tested') as $id => $tags) {
                $container->getDefinition($id)->setPublic(true);
            }
        }
    }

    // списибо Пятницеву Данилу   https://pyatnitsev.ru/2018/06/09/how-to-test-private-services-in-symfony/
    private function isPHPUnit(): bool
    {
        // defined by PHPUnit
        return defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PHPUNIT_PHAR__');
    }
}