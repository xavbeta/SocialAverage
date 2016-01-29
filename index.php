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
use SocialAverage\Inputs\InputChecker;
use SocialAverage\SlimExtensions\ErrorHandler;
use SocialAverage\SlimExtensions\Errors\BadRequestException;
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

$app->get('/token/:token_id', function ($token_id) use ($app) {

    $tm = new TokenManager();

    if(InputChecker::CheckTokenId($token_id)){
        print_r($tm->GetToken($token_id));
    } else {
       echo "$token_id";
       throw new BadRequestException();
    }

})->name("token")->conditions(array('token_id' => '[\w-]+'));

$app->post('/token/:action)', function ($action) use ($app) {

    $tm = new TokenManager();
    echo "$action";
    switch($action){
        case "generate":
            echo $tm->GetNewToken(rand(3,6));
            break;
        case "commit":
            echo  $tm->CommitToken("", 3);
            break;
        default:
            throw new \SocialAverage\SlimExtensions\Errors\PageNotFoundException();

    }

})->name("token")->conditions(array('action' => '(generate|commit)'));


$body = $app->request->getBody();

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

$app->error(function (\Exception $e) use ($app) {
    ErrorHandler::Handle($e, $app);
});

// Run the Slim application
$app->run();

