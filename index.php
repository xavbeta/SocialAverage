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
use SocialAverage\SlimExtensions\ErrorHandler;
use SocialAverage\Templates\SocialLoginTemplate;

error_reporting(-1);
ini_set('display_errors', 'On');

// Instantiate a Slim application
$app = new \Slim\Slim(array(
    'debug' => false
));

$app->get('/', function () use ($app) {
    SocialLoginTemplate::getInitTemplate();

    //return $this->view->render($response, 'main.html');

})->setName("index");

include_once ('endpoints/token.php');
include_once ('endpoints/node.php');
include_once ('endpoints/social.php');
include_once ('endpoints/share.php');

$app->error(function (\Exception $e) use ($app) {
    if($e instanceof \SocialAverage\SlimExtensions\Errors\HttpException){
        ErrorHandler::Handle($e, $app);
    } else {

    }
});

// Run the Slim application
$app->run();

