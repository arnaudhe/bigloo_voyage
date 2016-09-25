<?php

require_once __DIR__.'/bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Models\ModelFinder;
use Models\Trip;

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

$app->get('/', function () use ($app) 
{
    return $app->redirect('/trips');
})
->bind('home');

$app->get('/trips', function() use($app)
{	
    $trips = [];
    foreach (Trip::getAllTrips($app['db']) as $trip ) 
    {
        $trips[] = $trip->toArray();
    }

    return $app['twig']->render('trips.twig', ['trips' => $trips]);
})
->bind('trips');

$app->match('/trip/create', function(Request $request) use($app)
{
    $data = array(
        'name'  => '',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
                                ->add('name')
                                ->getForm();

    if ($request->isMethod('POST')) 
    {
        $form->handleRequest($request);

        if ($form->isValid())
        {
            $data = $form->getData();
            
            if (array_key_exists('name', $data))
            {
                $trip = new Trip($app['db'], ['name' => $data['name']]);
                return $app->redirect($app['url_generator']->generate('trip_update', ['trip' => $trip->getAttribute('id')]));
            }
            else
            {
                throw new Exception ("Missing parameter 'name'");
            }
        }
    }
    else
    {
        return $app['twig']->render('trip_create.twig', ['form' => $form->createView()]);
    }
}, 'GET|POST')
->bind('trip_create');

$app->get('/trip/{trip}', function($trip) use($app)
{
    $trip = new Trip($app['db'], ['id' => $trip]);
    
    $route = sprintf("/trip/%d/pic/0", $trip->getAttribute('id'));
    $subRequest = Request::create($route, 'GET');
    $pic_content = $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST, false)->getContent();

    return $app['twig']->render('trip.twig', ['trip' => $trip, 'pic_content' => $pic_content]);
})
->bind('trip');

$app->get('/trip/{trip}/pic/{pic}', function($trip, $pic) use($app)
{
    $trip = new Trip($app['db'], ['id' => $trip]);

    $picArray = $trip->getPic($pic);

    if (count($picArray) > 0)
    {
        $picContent = $app['twig']->render('trip_pic.twig', ['pic' => $picArray]);
    }
    else
    {
        $picContent = "No pics added to this trip";      
    }
    
    return $picContent;
})
->bind('trip_pic');


$app->match('/trip/update/{trip}', function (Request $request, $trip) use ($app)
{
    $form = $app['form.factory']->createBuilder('form')
                                ->add('description')
                                ->add('picture', 'file')
                                ->getForm();

    $request = $app['request'];
    $message = 'Upload a file';

    $trip = new Trip($app['db'], ['id' => $trip]);

    if ($request->isMethod('POST')) 
    {
        $form->handleRequest($request);
        
        if ($form->isValid())
        {
            $files       = $request->files->get($form->getName());
            $path        = __DIR__.'/../web/pics/';
            $filename    = pathinfo($files['picture']->getClientOriginalName());
            $newFileName = md5($filename['filename'] . time()) . '.' . strtolower($filename['extension']);
            $files['picture']->move($path, $newFileName);

            $description = $form->getData()['description'];
            
            $trip->addPic($newFileName, $description);
        }
    }

    return $app['twig']->render('trip_update.twig', ['trip' => $trip->toArray(), 'form' => $form->createView()]);
    
}, 'GET|POST')
->bind('trip_update');

$app->get('trip/delete/{trip}', function (Request $request, $trip) use ($app)
{
    $trip = new Trip($app['db'], ['id' => $trip]);
    
    //$trip->delete();

    return $app->redirect($app['url_generator']->generate('trips'));
})
->bind('trip_delete');


return $app;