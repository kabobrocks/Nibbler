<?php

/**
 *    Nibbler/Application wraps Silex Application
 */

use Symfony\Component\HttpFoundation\Request;
use Nibbler\Application;
use Nibbler\Pdo\PdoServiceProvider;
use Silex\Provider\AssetServiceProvider;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Nibbler\Application();

//using debug
$app['debug'] = true;

$request = Request::create($_SERVER['REQUEST_URI'], 'REQUEST');

#####################################################################
##   Service Injections
#####################################################################
$app['user.auth'] = function ($app) {
    return new Nibbler\Auth($app);
};

$app['nibbler.nibbles'] = $app->protect(function (Application $app, $userID)    {
    return $app->getPosts($userID);
});

$app['nibbler.userInfo'] = $app->protect(function (Application $app, $userID) {
    return $app->getUserInfo($userID);
});

$app['nibbler.numNibbles'] = $app->protect(function (Application $app, $userID) {
    return $app->getNumNibbles($userID);
});

$app['nibbler.allNibbles'] = $app->protect(function (Application $app) {
    return $app->getAllPosts();
});


#####################################################################
#   Service Provider, Database, PDO driver
#####################################################################
$app->register(new PdoServiceProvider(),
    array(
        'pdo.dsn' => 'mysql:host=localhost;dbname=nibbler',
        'pdo.username' => 'nibbler',
        'pdo.password' => 'password',
    )
);

##   service provider, assets
$app->register(new AssetServiceProvider());

##   service provider, twig templates
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
));

##  service provider, session data
$app->register(new Silex\Provider\SessionServiceProvider());


#####################################################################
##   Routing
#####################################################################

##   Login
$app->get('/login', function (Request $request) use ($app) {

    if( $user = $app['user.auth']->checkAuth($app, $request)) {

        $app['session']->set('message', '');
        $app['session']->set('user', array('username' => $user['handle'], 'userid' => $user['userid']));

        return $app->redirect('/account');
    }

    $app['session']->set('message', 'Invalid Login, try again.');

    return $app->redirect('/');

})->method('POST');

##   Logout
$app->get('/logout', function (Request $request) use ($app) {

    $app['session']->set('user', array());
    $app['session']->invalidate(1);

    return $app->redirect('/');
});

##   Account
$app->get('/account', function () use ($app) {
    if (!$user = $app['session']->get('user')) {
        return $app->redirect('/');
    }

    return $app['twig']->render('account.html.twig', array(
        'name' => $user['username'],
        'nibbles' => $app['nibbler.nibbles']($app, $user['userid']),
        'userInfo' => $app['nibbler.userInfo']($app, $user['userid']),
        'numNibbles' => $app['nibbler.numNibbles']($app, $user['userid'])
    ));
});

##   All
$app->get('/all', function () use ($app) {
    if (!$user = $app['session']->get('user')) {
        return $app->redirect('/');
    }

    return $app['twig']->render('all.html.twig', array(
        'name' => $user['username'],
        'nibbles' => $app['nibbler.allNibbles']($app)
    ));
});

##   Default
$app->get('/', function (Nibbler\Application $app , Request $request)  {

    return $app['twig']->render('landing.html.twig', array(
        'message' => $app['session']->get('message')
    ));
});

##   Post Nibble
$app->get('/post', function (Nibbler\Application $app , Request $request)  {

    $post = $request->get('chars');

    if (!$user = $app['session']->get('user')) {
        // User not authenticated
        return $app->json(array('error' => 'Your session appears to be invalid.'));
    }


    if(strlen($post) > 140) {
        // Bypassed the front end to post a Nibble that is too long
        return $app->json(array('error' => 'Your Nibble seems to larger than it is allowed.'));
    }

    if($app->savePost($post, $user['userid'])) {

        // Supply Nibble response to populate Nibble on page using ajax
        return $app->json(array('post' => $post, 'date' => date('M d y')));
    }

    // If it got to this point, an error has occurred
    return $app->json(array('error' => 'Could not post Nibble'));

})->method('POST');

#####################################################################


$app->run();
