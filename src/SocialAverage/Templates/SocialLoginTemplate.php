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

    public static function getInitTemplate($redirectUrl = null, $exclude = array()) {
        $suffix = "";
        if($redirectUrl && strlen($redirectUrl) > 0){
            $suffix = "?url=".$redirectUrl;
        }

        if(count($exclude)< 6):
        ?>
                <?php if(!in_array(SocialNetwork::Twitter, $exclude)): ?>
                    <a class="btn btn-block btn-social btn-lg btn-twitter" href="login/twitter<?php echo $suffix; ?>" >
                        <span class="fa fa-twitter"></span> Sign in with Twitter
                    </a>
                <?php endif; ?>

                <?php if(!in_array(SocialNetwork::Facebook, $exclude)): ?>
                    <a class="btn btn-block btn-social btn-lg btn-facebook" href="login/facebook<?php echo $suffix; ?>" >
                        <span class="fa fa-facebook"></span> Sign in with Facebook
                    </a>
                <?php endif; ?>

                <?php if(!in_array(SocialNetwork::Google, $exclude)): ?>
                    <a class="btn btn-block btn-social btn-lg btn-google" href="login/google<?php echo $suffix; ?>" >
                        <span class="fa fa-google"></span> Sign in with Google
                    </a>
                <?php endif; ?>

                <?php if(!in_array(SocialNetwork::OpenID, $exclude)): ?>
                    <a class="btn btn-block btn-social btn-lg btn-openid" href="login/openid<?php echo $suffix; ?>" >
                        <span class="fa fa-openid"></span> Sign in with OpenID
                    </a>
                <?php endif; ?>

                <?php if(!in_array(SocialNetwork::LinkedIn, $exclude)): ?>
                    <a class="btn btn-block btn-social btn-lg btn-linkedin" href="login/linkedin<?php echo $suffix; ?>" >
                        <span class="fa fa-linkedin"></span> Sign in with LinkedIn
                    </a>
                <?php endif; ?>

                <?php if(!in_array(SocialNetwork::Instagram, $exclude)): ?>
                    <a class="btn btn-block btn-social btn-lg btn-instagram" href="login/instagram<?php echo $suffix; ?>" >
                        <span class="fa fa-instagram"></span> Sign in with Instagram
                    </a>
                <?php endif; ?>

        <?php
            else:
        ?>
                <p>
                    Already registered!
                </p>

        <?php
            endif;
    }
}