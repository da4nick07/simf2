<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

define( 'TEST_USER_ID', 2);
define( 'TEST_ADMIN_ID', 3);

define( 'TEST_USER_POST_ID', 1);
define( 'TEST_ADMIN_POST_ID', 2);
