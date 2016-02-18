<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 17:45
 */

namespace SocialAverage\Templates;

use Slim\Slim;
use SocialAverage\Authentication\AuthenticationManager;
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

    private static function handleLoginUserData(Slim $app, $userData, $redirectUrl = null) {

        if(!$userData instanceof ErrorWrapper){
            echo "<pre>".print_r($userData, true)."</pre>";

            exit(0);

            //TODO: is new user? then add node and account then authenticate then redirect to addaccount page

            //TODO: is registration phase (authenticated but no transaction yet)?
            // then add account then redirect to addaccount page
            // else IllegalRequestException!


            AuthenticationManager::Authenticate(4, $app);


            if($redirectUrl || strlen($redirectUrl) > 0) {
                echo $redirectUrl;
                $app->redirect("$redirectUrl");
            } else {
                $app->redirect($app->request->getRootUri());
            }
        } else {
            echo $userData->message;
        }
    }

    public static function doTwitterLogin(Slim $app,$redirectUrl = null) {
        $twitterWrap = new TwitterLoginWrapper();
        $userData = $twitterWrap->getData();

        static::handleLoginUserData($app, $userData, $redirectUrl);
    }

    public static function doFacebookLogin(Slim $app,$redirectUrl = null) {
        $fbWrap = new FacebookLoginWrapper();
        $userData = $fbWrap->getData();

        static::handleLoginUserData($app, $userData, $redirectUrl);
    }

    public static function doGoogleLogin(Slim $app,$redirectUrl = null) {
        $googleWrap = new GoogleLoginWrapper();
        $userData = $googleWrap->getData();

        static::handleLoginUserData($app, $userData, $redirectUrl);
    }

    public static function doOpenIDLogin(Slim $app,$redirectUrl = null) {
        $openidWrap = new OpenIDLoginWrapper();
        $userData = $openidWrap->getData();

        static::handleLoginUserData($app, $userData, $redirectUrl);
    }

    public static function doLinkedInLogin(Slim $app,$redirectUrl = null) {
        $linkedinWrap = new LinkedInLoginWrapper();
        $userData = $linkedinWrap->getData();

        static::handleLoginUserData($app, $userData, $redirectUrl);
    }

    public static function doInstagramLogin(Slim $app, $redirectUrl = null) {
        $instagramWrap = new InstagramLoginWrapper();
        $userData = $instagramWrap->getData();

        static::handleLoginUserData($app, $userData, $redirectUrl);
    }

    public static function getInitTemplate($redirectUrl = null) {
        $suffix = "";
        if($redirectUrl && strlen($redirectUrl) > 0 && false){
            $suffix = "?url=".$redirectUrl;
        }
        ?>
            <html>
                <body>
                    <ul>
                        <li><a href="login/twitter<?php echo $suffix; ?>">login con Twitter</a></li>
                        <li><a href="login/facebook<?php echo $suffix; ?>">login con Facebook</a></li>
                        <li><a href="login/google<?php echo $suffix; ?>">login con Google</a></li>
                        <li><a href="login/openid<?php echo $suffix; ?>">login con OpenID</a></li>
                        <li><a href="login/linkedin<?php echo $suffix; ?>">login con LinkedIn</a></li>
                        <li><a href="login/instagram<?php echo $suffix; ?>">login con Instagram</a></li>
                    </ul>
                </body>
            </html>
        <?php
    }
}