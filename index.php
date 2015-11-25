<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 15:39
 */


use SocialAverage\Templates\SocialSharerTemplate;
use SocialAverage\Templates\SocialLoginTemplate;

error_reporting(-1);
ini_set('display_errors', 'On');

// Autoload
require 'vendor/autoload.php';

// Instantiate a Slim application
$app = new \Slim\Slim(array(
    'debug' => true
));

$app->get('/', function () {
    SocialLoginTemplate::getInitTemplate();
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
    }
});

$app->get('/share', function () {
    echo SocialSharerTemplate::getAllSharerTemplate("no text");
});

// Run the Slim application
$app->run();

