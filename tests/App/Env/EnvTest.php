<?php

namespace App\Env;

use App\Service\SpamChecker;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EnvTest extends WebTestCase
{
    public function testEnv()
    {

/*
        // Не работает...
        // Не могу найти как подключить .env.local
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
*/
        // странно, а  debug:dotenv показывает...
//        echo PHP_EOL . $_ENV['LOCAL_VAR'] . PHP_EOL;

        $this->assertTrue( isset($_ENV['DATABASE_URL']));
        $this->assertTrue( isset($_ENV['VVV_ENV_VAR']));
        $this->assertEquals( 'VVV_ENV_VAR_123',  $_ENV['VVV_ENV_VAR']);
        //  не работает
//        $this->assertEquals( 'VVV_ENV_VAR_123',  getenv('VVV_ENV_VAR', true));
//        $this->assertEquals( 'VVV_ENV_VAR_123',  getenv('VVV_ENV_VAR'));


        self::bootKernel();
        // returns the real and unchanged service container
        $container = self::$kernel->getContainer();
        // приходиться делать сервис публичным...
        $this->assertTrue($container->has(SpamChecker::class));
        //  это мы достаём параметр конфигурации
        $this->assertTrue($container->hasParameter('app.test_param'));
        $this->assertEquals('app.test_param123', $container->getParameter('app.test_param'));

        // а как достать параметр/ аргумент сервиса?
//        $this->assertTrue($container->hasParameter('$akismetKey'));

    }

}
