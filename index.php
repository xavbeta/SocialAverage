<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 15:39
 */


// Autoload
use SocialAverage\Authentication\AuthenticationManager;

require 'vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', 'On');

// Instantiate a Slim application
$app = new \Slim\Slim(array(
    'debug' => true
));

$app->get('/', function () use ($app) {
    //AuthenticationManager::Authenticate(4, $app);
    AuthenticationManager::Verify($app);
    $app->render('home.php');

})->setName("index");

include_once ('endpoints/token.php');
include_once ('endpoints/node.php');
include_once ('endpoints/social.php');
include_once ('endpoints/share.php');
include_once ('endpoints/error.php');

// Run the Slim application
$app->run();

