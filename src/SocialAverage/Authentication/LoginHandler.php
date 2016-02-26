<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 26/02/2016
 * Time: 14:50
 */

namespace SocialAverage\Authentication;


use Slim\Slim;
use SocialAverage\Nodes\NodeManager;
use SocialAverage\SlimExtensions\Errors\IllegalRequestException;
use SocialAverage\SlimExtensions\Errors\UnauthenticatedRequestException;
use SocialAverage\Socials\ErrorWrapper;
use SocialAverage\Socials\SocialNetwork;
use SocialAverage\Values\UniformIntegerGenerator;

class LoginHandler
{

    public static function HandleLogin(Slim $app, $userData, $redirectUrl = null, $social) {

        if(!$userData instanceof ErrorWrapper){
            echo "<pre>".print_r($userData, true)."</pre>";

            self::HandleSuccessfulLogin($app, $userData, $redirectUrl, $social);

        } else {
            self::HandleErrorLogin($app, $userData, $redirectUrl, $social);

        }
    }

    private static function HandleSuccessfulLogin(Slim $app, $userData, $redirectUrl, $social) {
        //checking authentication
        self::CheckAuthentication($app);

        // checking account existance
        $node = self::CheckAccountExistence($userData->identifier);
        echo "node: ". print_r($node, true);

        // it's the registration
        if(self::IsFirstRegistration($app, $node)){
            self::DoFirstRegistration($app, $userData, $social);
        }
        // it's in registration phase (from addaccount page)
        else if (self::IsSecondaryRegistration($app, $node)){
            self::DoSecondaryRegistration($app, $userData, $social);
        }
        // it's a login
        else if (self::IsLogin($node)) {
            self::DoLogin($app, $node, $redirectUrl);
        }
        // action not allowed
        else {
            throw new IllegalRequestException();
        }

    }

    private static function HandleErrorLogin($app, $userData, $redirectUrl, $social)
    {
        echo $userData->message;
    }

    private static function CheckAuthentication($app)
    {
        try {
            AuthenticationManager::Verify($app);
        } catch (UnauthenticatedRequestException $ex){
            //do nothing
        }
    }

    private static function CheckAccountExistence($identifier)
    {
        $nm = NodeManager::GetInstance();
        return $nm->FindNodeByAccount($identifier, false);
    }

    private static function IsFirstRegistration($app, $node) {
        return !AuthenticationManager::IsAuthenticated($app)    // is not authenticated
                && !$node;                                      // and it's not present in db
    }

    private static function DoFirstRegistration(Slim $app, $userData, $social) {
        echo "#1#";

        // add node and account
        $nm = NodeManager::GetInstance();
        $nodeId = $nm->AddNode(new UniformIntegerGenerator());
        $nm->AddAccount($nodeId, $social, $userData->identifier, $userData->displayName, $userData->photoURL, json_encode($userData));

        // authenticate
        AuthenticationManager::Authenticate($nodeId, $app);

        // redirect to addAccount
        $app->redirect('/addaccount');
    }

    private static function IsSecondaryRegistration($app, $node) {
        $nm = NodeManager::GetInstance();
        return AuthenticationManager::IsAuthenticated($app)     // it's authenticated
                && !$node                                       // and it's not present in db
                && $nm->GetTransactionsCount($app->node) == 0;  //no transaction yes
    }

    private static function DoSecondaryRegistration(Slim $app, $userData, $social)
    {
        echo "#2#";

        $nm = NodeManager::GetInstance();
        // add account
        $nm->AddAccount($app->node, $social, $userData->identifier, $userData->displayName, $userData->photoURL, json_encode($userData));

        // redirect to addAccount
        $app->redirect('/addaccount');
    }

    private static function IsLogin($node)
    {
        return $node ? true: false;
    }

    private static function DoLogin(Slim $app, $node, $redirectUrl)
    {
        echo "#3#";
        // authenticate
        AuthenticationManager::Authenticate($node->node_id, $app);

        // redirect to home or redirect
        if($redirectUrl || strlen($redirectUrl) > 0) {
            echo $redirectUrl;
            $app->redirect("$redirectUrl");
        } else {
            $app->redirect($app->urlFor("index"));
        }
    }

}