<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 17:45
 */

namespace SocialAverage\Templates;

use SocialAverage\Socials\ErrorWrapper;
use SocialAverage\Socials\FacebookLoginWrapper;
use SocialAverage\Socials\GoogleLoginWrapper;
use SocialAverage\Socials\InstagramLoginWrapper;
use SocialAverage\Socials\LinkedInLoginWrapper;
use SocialAverage\Socials\OpenIDLoginWrapper;
use SocialAverage\Socials\TwitterLoginWrapper;

require_once("vendor/hybridauth/hybridauth/hybridauth/Hybrid/Endpoint.php");
require_once("vendor/hybridauth/hybridauth/hybridauth/Hybrid/Auth.php");


class SocialLoginTemplate
{

    private static $config_path = "Config/hybridauth_config.php";

    private static function handleLoginUserData($userData) {

        if(!$userData instanceof ErrorWrapper){
            echo "<pre>".print_r($userData, true)."</pre>";
        } else {
            echo $userData->message;
        }
    }

    public static function doTwitterLogin() {

        $twitterWrap = new TwitterLoginWrapper();
        $userData = $twitterWrap->getData();

        static::handleLoginUserData($userData);
    }

    public static function doFacebookLogin() {
        $fbWrap = new FacebookLoginWrapper();
        $userData = $fbWrap->getData();

        static::handleLoginUserData($userData);
    }

    public static function doGoogleLogin() {
        $googleWrap = new GoogleLoginWrapper();
        $userData = $googleWrap->getData();

        static::handleLoginUserData($userData);
    }

    public static function doOpenIDLogin() {
        $openidWrap = new OpenIDLoginWrapper();
        $userData = $openidWrap->getData();

        static::handleLoginUserData($userData);
    }

    public static function doLinkedInLogin() {
        $linkedinWrap = new LinkedInLoginWrapper();
        $userData = $linkedinWrap->getData();

        static::handleLoginUserData($userData);
    }

    public static function doInstagramLogin() {
        $instagramWrap = new InstagramLoginWrapper();
        $userData = $instagramWrap->getData();

        static::handleLoginUserData($userData);
    }

    public static function getInitTemplate() {
        ?>
            <html>
                <body>
                    <ul>
                        <li><a href="login/twitter">login con Twitter</a></li>
                        <li><a href="login/facebook">login con Facebook</a></li>
                        <li><a href="login/google">login con Google</a></li>
                        <li><a href="login/openid">login con OpenID</a></li>
                        <li><a href="login/linkedin">login con LinkedIn</a></li>
                        <li><a href="login/instagram">login con Instagram</a></li>
                    </ul>
                </body>
            </html>
        <?php
    }
}