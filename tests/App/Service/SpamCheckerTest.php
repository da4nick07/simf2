<?php

namespace App\Service;

use App\Service\SpamChecker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Definition;

class SpamCheckerTest extends WebTestCase
{
    public function testByName()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->assertTrue($container->has(SpamChecker::class));
        /** @var  $spamChecker SpamChecker */
        $spamChecker = $container->get(SpamChecker::class);
        $s = $spamChecker->getEndpoint();

        echo 'Endpoint = ' . $s . PHP_EOL;

    }

    public function testByAlias()
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->assertTrue($container->has('test.SpamChecker'));
        /** @var  $spamChecker SpamChecker */
        $spamChecker = $container->get('test.SpamChecker');
        $s = $spamChecker->getEndpoint();

        echo 'Endpoint = ' . $s . PHP_EOL;

        // а как достать параметр/ аргумент сервиса?
        //  не работает...
//        $this->assertTrue($container->hasParameter('akismetKey'));
//        $this->assertTrue($container->has('akismetKey'));

//        $this->assertTrue($container->hasParameter('$akismetKey'));
//        $this->assertTrue($container->has('$akismetKey'));


        /** @var  $bag ParameterBagInterface */
        $bag = $container->getParameterBag();
        echo $bag->get('app.test_param') . PHP_EOL;

    }

    public function testGetSpamScore()
    {
        $stub = $this->createStub(SpamChecker::class);
        $stub->method('getSpamScore')
            ->willReturn(2);

        // Calling $stub->doSomething() will now return
        // 'foo'.
        $this->assertSame('foo', $stub->doSomething());
    }
}
