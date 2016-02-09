<?php

use SocialAverage\SlimExtensions\Errors\PageNotFoundException;
use SocialAverage\Templates\SocialLoginTemplate;

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