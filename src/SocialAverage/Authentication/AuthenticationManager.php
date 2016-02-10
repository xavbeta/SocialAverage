<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 13:06
 */

namespace SocialAverage\Authentication;


use Slim\Slim;
use SocialAverage\Inputs\InputChecker;
use SocialAverage\SlimExtensions\Errors\UnauthenticatedRequestException;
use Webpatser\Uuid\Uuid;

class AuthenticationManager
{

    public static function Verify(Slim $app){
        $cookie = $app->getCookie('slim_session');
        $nodeId = AuthenticationSalt::Remove($cookie);

        if($nodeId == null || !InputChecker::CheckNodeId($nodeId, true)){
            throw new UnauthenticatedRequestException();
        }

        $app->node = $nodeId;

    }

    public static function Authenticate($nodeId,Slim $app){
        $app->setCookie('slim_session',AuthenticationSalt::Apply($nodeId),'1 day', '/', null, false, false);
        /*$app->add(new \Slim\Middleware\SessionCookie(array(
            'expires' => '1 day',
            'path' => '/',
            'domain' => null,
            'secure' => false,
            'httponly' => false,
            'name' => 'slim_session',
            'secret' => Uuid::generate().'-'.$nodeId,
            'cipher' => MCRYPT_RIJNDAEL_256,
            'cipher_mode' => MCRYPT_MODE_CBC
        )));*/
    }
}

class AuthenticationSalt {

    public static function Apply($nodeId){
        return Uuid::generate().'-'.$nodeId;
    }

    public static function Remove($saltednodeId){
        $nodeIdStart = strrchr($saltednodeId, "-");
        if($nodeIdStart && strlen($nodeIdStart) > 1){
            return substr($nodeIdStart, 1);
        }

        return null;
    }
}