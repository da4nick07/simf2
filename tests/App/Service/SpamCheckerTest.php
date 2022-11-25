<?php

namespace App\Service;

use App\Service\SpamChecker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\Definition;

class SpamCheckerTest extends WebTestCase
{
    // так нельзя...
//    public function testSpamChecker(SpamChecker $spamChecker)
    public function testSpamChecker()
    {
        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();

        // приходиться делать сервис публичным...
//        $c = $container->has('App\Service\SpamChecker');
        $this->assertTrue($container->has(SpamChecker::class));
        /** @var  $spamChecker SpamChecker */
        $spamChecker = $container->get(SpamChecker::class);
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
        echo $bag->get('app.test_param');

    }

}
