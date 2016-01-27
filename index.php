<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 15:39
 */


// Autoload
require 'vendor/autoload.php';

//use Slim\Views\Twig;
use SocialAverage\Templates\SocialSharerTemplate;
use SocialAverage\Templates\SocialLoginTemplate;
use SocialAverage\Tokens\TokenManager;

error_reporting(-1);
ini_set('display_errors', 'On');

// Instantiate a Slim application
$app = new \Slim\Slim(array(
    'debug' => true
));

// Register component on container
/*$app->container['view'] = function ($c) {
    $view = new  Twig("templates");
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUrl()
    ));

    return $view;
};*/


$app->get('/', function () use ($app) {
    SocialLoginTemplate::getInitTemplate();

    //return $this->view->render($response, 'main.html');

})->setName("index");

$app->get('/token', function () use ($app) {

    $tm = new TokenManager();
    echo $tm->getNewToken(rand(2,100));

})->setName("token");

$app->get('/login/:social', function ($social) use ($app) {
    switch($social) {
        case "twitter":
            SocialLoginTemplate::doTwitterLogin();
            break;
        case "facebook":
            SocialLoginTemplate::doFacebookLogin();
            break;
        case "google":
            SocialLoginTemplate::doGoogleLogin();
            break;
        case "openid":
            SocialLoginTemplate::doOpenIDLogin();
            break;
        case "linkedin":
            SocialLoginTemplate::doLinkedInLogin();
            break;
        case "instagram":
            SocialLoginTemplate::doInstagramLogin();
            break;
    }
});

$app->get('/share', function () {
    echo SocialSharerTemplate::getAllSharerTemplate("no text");
});

// Run the Slim application
$app->run();

