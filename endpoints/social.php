<?php

use SocialAverage\SlimExtensions\Errors\PageNotFoundException;
use SocialAverage\Templates\SocialLoginTemplate;

$app->get('/login', function () use ($app) {
    $app->render('login.php', array("redirect_url" => $app->request()->get('url')));
})->setName('login');

$app->get('/login/:social', function ($social) use ($app) {

    $redirectUrl = urldecode($app->request()->get('url'));

    switch($social) {
        case "twitter":
            SocialLoginTemplate::doTwitterLogin($app, $redirectUrl);
            break;
        case "facebook":
            SocialLoginTemplate::doFacebookLogin($app, $redirectUrl);
            break;
        case "google":
            SocialLoginTemplate::doGoogleLogin($app, $redirectUrl);
            break;
        case "openid":
            SocialLoginTemplate::doOpenIDLogin($app, $redirectUrl);
            break;
        case "linkedin":
            SocialLoginTemplate::doLinkedInLogin($app, $redirectUrl);
            break;
        case "instagram":
            SocialLoginTemplate::doInstagramLogin($app, $redirectUrl);
            break;
        default:
            //echo $social."------".$redirectUrl;
            throw new PageNotFoundException($app->request->getUrl() + $redirectUrl);
    }
});