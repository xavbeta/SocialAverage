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
use SocialAverage\Inputs\DataExtractor;
use SocialAverage\Inputs\InputChecker;
use SocialAverage\Nodes\NodeManager;
use SocialAverage\SlimExtensions\ErrorHandler;
use SocialAverage\SlimExtensions\Errors\BadRequestException;
use SocialAverage\SlimExtensions\Errors\PageNotFoundException;
use SocialAverage\Templates\SocialSharerTemplate;
use SocialAverage\Templates\SocialLoginTemplate;
use SocialAverage\Tokens\TokenManager;


error_reporting(-1);
ini_set('display_errors', 'On');

// Instantiate a Slim application
$app = new \Slim\Slim(array(
    'debug' => true
));

$app->get('/', function () use ($app) {
    SocialLoginTemplate::getInitTemplate();

    //return $this->view->render($response, 'main.html');

})->setName("index");

$app->get('/token/:token_id', function ($token_id) use ($app) {

    $tm = new TokenManager();

    if(InputChecker::CheckTokenId($token_id)){
        echo json_encode($tm->GetToken($token_id));
    } else {
       throw new BadRequestException();
    }

})->name("token")->conditions(array('token_id' => '[\w-]+'));

$app->post('/token/generate', function () use ($app) {
    $userId = DataExtractor::ExtractNodeId($app->request->getBody());

    if(InputChecker::CheckNodeId($userId)) {
        $tm = new TokenManager();
        echo $tm->GetNewToken($userId);
    } else {
        throw new BadRequestException();
    }
});

$app->post('/token/commit', function () use ($app) {

    $body = $app->request->getBody();
    $nodeId = DataExtractor::ExtractNodeId($body);
    $tokenId = DataExtractor::ExtractTokenId($body);

    if(InputChecker::CheckNodeId($nodeId)
        && InputChecker::CheckTokenId($tokenId)) {
        $tm = new TokenManager();
        echo  $tm->CommitToken($tokenId, $nodeId);
    } else {
        throw new BadRequestException();
    }

});

$app->get('/node/:nodeId', function ($nodeId) use ($app) {

    if(InputChecker::CheckNodeId($nodeId)) {
        $nm = new NodeManager();
        echo json_encode($nm->GetNode($nodeId));
    } else {
        throw new BadRequestException();
    }
});

$app->post('/node/history', function () use ($app) {
    $nodeId = DataExtractor::ExtractNodeId($app->request->getBody());

    if(InputChecker::CheckNodeId($nodeId)) {
        $nm = new NodeManager();
        echo json_encode($nm->GetNodeHistory($nodeId));
    } else {
        throw new BadRequestException();
    }
});

$app->post('/node/addaccount', function () use ($app) {

});


$app->post('/node/iswaiting', function () use ($app) {
    $nodeId = DataExtractor::ExtractNodeId($app->request->getBody());

    if(InputChecker::CheckNodeId($nodeId)) {
        $nm = new NodeManager();
        echo json_encode($nm->HasOpenTransaction($nodeId));
    } else {
        throw new BadRequestException();
    }
});

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
        default:
            throw new PageNotFoundException();
    }
});

$app->get('/share', function () {
    echo SocialSharerTemplate::getAllSharerTemplate("no text");
});

$app->error(function (\Exception $e) use ($app) {
    if($e instanceof \SocialAverage\SlimExtensions\Errors\HttpException){
        ErrorHandler::Handle($e, $app);
    }
});

// Run the Slim application
$app->run();

