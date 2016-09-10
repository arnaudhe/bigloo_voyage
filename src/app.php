<?php

require_once __DIR__.'/bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

set_time_limit(180); //Augmente le timeout à 120 secondes

function sendMail($app, $to, $subject, $content)
{
    try
    {
        $app['mailer']->send(\Swift_Message::newInstance()
                      ->setSubject($subject)
                      ->setFrom(array('john@doe.com' => 'John Doe')) // replace with your own
                      ->setSender('john@doe.com')
                      ->setReplyTo('john@doe.com')
                      ->setTo($to)   // replace with email recipient
                      ->setBody($content));
        return "OK";
    }
    catch (\Exception $e)
    {
        return $app['twig']->render('logs_error.twig', array('message'=>$e->getMessage()));
    }
}

$app->error(function (\Exception $e, $code) use($app)
{
    switch ($code)
    {
        case 404:
            return new Response($app['twig']->render('error.twig', array('code' => '404', 'message' => 'The requested page could not be found.')));
            break;

        default:
            //return new Response($app['twig']->render('error.twig', array('code' => 'Unknow error', 'message' => 'We are sorry, but something went terribly wrong.')));
            break;
    }
});

$app->get('/', function() use($app)
{	
    return $app['twig']->render('home.twig', []);
})
->bind('home');




return $app;