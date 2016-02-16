<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 15:39
 */


// Autoload
use SocialAverage\Authentication\AuthenticationManager;
use SocialAverage\Inputs\DataExtractor;
use SocialAverage\Inputs\DataPostExtractor;
use SocialAverage\Inputs\InputChecker;
use SocialAverage\SlimExtensions\Errors\BadRequestException;
use SocialAverage\SlimExtensions\Errors\InvalidTokenException;
use SocialAverage\SlimExtensions\Errors\SpoiledTokenException;
use SocialAverage\Tokens\TokenManager;

require 'vendor/autoload.php';

error_reporting(-1);
ini_set('display_errors', 'On');

// Instantiate a Slim application
$app = new \Slim\Slim(array(
    'debug' => false
));

$app->get('/', function () use ($app) {
    AuthenticationManager::Authenticate(3, $app);
    AuthenticationManager::Verify($app);
    $app->render('home.php', array("nodeId" => $app->node, "generateTokenUrl" => $app->urlFor("initiate"), "redeemTokenUrl" => $app->urlFor("redeem")));

})->setName("index");

$app->get('/redeem(/:token)', function ($token = null) use ($app) {
    AuthenticationManager::Verify($app);
    $app->render('redeem.php', array("nodeId" => $app->node, "token"=>$token, "commitUrl" => $app->urlFor("commit")));

})->setName("redeem");


$app->post('/commit', function () use ($app) {
    AuthenticationManager::Verify($app);

    $tokenId = DataPostExtractor::ExtractTokenId($app->request);
    print_r($tokenId);

    if(InputChecker::CheckTokenId($tokenId)) {
        $tm = new TokenManager();
        $tm->CommitToken($tokenId, $app->node);
        $app->redirect("/");

    } else {
        throw new InvalidTokenException();
    }

})->setName("commit");

$app->get('/initiate', function () use ($app) {
    AuthenticationManager::Verify($app);

    $tm = new TokenManager();
    $tm->GetNewToken($app->node);

    $app->redirect("/");

})->setName("initiate");

$app->get('/spoiled(/:token)', function ($token = null) use ($app) {

    $app->render('errors/spoiled_token.php', array("nodeId" => $app->node, "token"=>$token, "homeUrl" => $app->urlFor("index")));

})->setName("spoiled");

$app->get('/invalid(/:token)', function ($token = null) use ($app) {

    $app->render('errors/invalid_token.php', array("nodeId" => $app->node, "token"=>$token, "homeUrl" => $app->urlFor("index")));

})->setName("invalid");


//include_once ('endpoints/token.php');
//include_once ('endpoints/node.php');
//include_once ('endpoints/social.php');
//include_once ('endpoints/share.php');
include_once ('endpoints/error.php');

// Run the Slim application
$app->run();

