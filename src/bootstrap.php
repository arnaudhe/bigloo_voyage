<?php

require_once __DIR__.'/../vendor/autoload.php';


use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\TranslationServiceProvider;

$app = new Silex\Application();

$app['debug'] = true;

/***********************************/
/* SERVICES PROVIDERS REGISTRATION */
/***********************************/

//Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/views',
));

//Twig url generation from route alias
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

//Doctrine DBAL
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
       	'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'bigloo',
        'user'      => 'root',
        'password'  => '123456',
        'charset'   => 'utf8',
    ),
));

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app->register(new SwiftmailerServiceProvider());

$app->register(new FormServiceProvider());

$app['swiftmailer.options'] = array(
    'host'       => 'smtp.gmail.com',
    'port'       => 465,
    'username'   => 'test@gmail.com',
    'password'   => 'password',
    'encryption' => 'ssl',
    'auth_mode'  => 'login'
);

