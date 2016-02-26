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
use SocialAverage\Authentication\LoginHandler;
use SocialAverage\Nodes\NodeManager;
use SocialAverage\SlimExtensions\Errors\IllegalRequestException;
use SocialAverage\SlimExtensions\Errors\UnauthenticatedRequestException;
use SocialAverage\Socials\ErrorWrapper;
use SocialAverage\Socials\FacebookLoginWrapper;
use SocialAverage\Socials\GoogleLoginWrapper;
use SocialAverage\Socials\InstagramLoginWrapper;
use SocialAverage\Socials\LinkedInLoginWrapper;
use SocialAverage\Socials\OpenIDLoginWrapper;
use SocialAverage\Socials\SocialNetwork;
use SocialAverage\Socials\TwitterLoginWrapper;
use SocialAverage\Values\UniformIntegerGenerator;

require_once("vendor/hybridauth/hybridauth/hybridauth/Hybrid/Endpoint.php");
require_once("vendor/hybridauth/hybridauth/hybridauth/Hybrid/Auth.php");


class SocialLoginTemplate
{

    private static $config_path = "Config/hybridauth_config.php";

    public static function doTwitterLogin(Slim $app,$redirectUrl = null) {
        $twitterWrap = new TwitterLoginWrapper();
        $userData = $twitterWrap->getData();

        LoginHandler::HandleLogin($app, $userData, $redirectUrl, SocialNetwork::Twitter);
    }

    public static function doFacebookLogin(Slim $app,$redirectUrl = null) {
        $fbWrap = new FacebookLoginWrapper();
        $userData = $fbWrap->getData();

        LoginHandler::HandleLogin($app, $userData, $redirectUrl, SocialNetwork::Facebook);
    }

    public static function doGoogleLogin(Slim $app,$redirectUrl = null) {
        $googleWrap = new GoogleLoginWrapper();
        $userData = $googleWrap->getData();

        LoginHandler::HandleLogin($app, $userData, $redirectUrl, SocialNetwork::Google);
    }

    public static function doOpenIDLogin(Slim $app,$redirectUrl = null) {
        $openidWrap = new OpenIDLoginWrapper();
        $userData = $openidWrap->getData();

        LoginHandler::HandleLogin($app, $userData, $redirectUrl,  SocialNetwork::OpenID);
    }

    public static function doLinkedInLogin(Slim $app,$redirectUrl = null) {
        $linkedinWrap = new LinkedInLoginWrapper();
        $userData = $linkedinWrap->getData();

        LoginHandler::HandleLogin($app, $userData, $redirectUrl,  SocialNetwork::LinkedIn);
    }

    public static function doInstagramLogin(Slim $app, $redirectUrl = null) {
        $instagramWrap = new InstagramLoginWrapper();
        $userData = $instagramWrap->getData();

        LoginHandler::HandleLogin($app, $userData, $redirectUrl, SocialNetwork::Instagram);
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