<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 24/11/2015
 * Time: 13:33
 */

namespace SocialAverage;

require_once "ErrorWrapper.php";

abstract class ILoginWrapper
{

    protected static $config_path = "config/hybridauth_config.php";

    abstract protected function doLogin(\Hybrid_Auth $hybridauth);

    abstract protected function getCallbackURL($base_path);

    public function getData() {

        if (isset($_REQUEST['hauth_start']) || isset($_REQUEST['hauth_done'])) {

            \Hybrid_Endpoint::process();

        } else {

            session_start();

            try {

                $config = include ILoginWrapper::$config_path;
                $config['base_url'] = $this->getCallbackURL($config['base_url']);

                // hybridauth EP
                $hybridauth = new \Hybrid_Auth($config);

                $social = $this->doLogin($hybridauth);

                // return TRUE or False <= generally will be used to check if the user is connected to the social before getting user profile, posting stuffs, etc..
                if(!$social->isUserConnected()) {
                    return new \SocialAverage\ErrorWrapper("User not connected.");
                }

                // get the user profile
                $user_profile = $social->getUserProfile();

                //self::echoUserDetails($user_profile);

                return $user_profile;
                // logout
                //echo "Logging out..";
                //$social->logout();

            } catch (\Exception $e) {
                // In case we have errors 6 or 7, then we have to use Hybrid_Provider_Adapter::logout() to
                // let hybridauth forget all about the user so we can try to authenticate again.

                // Display the recived error,
                // to know more please refer to Exceptions handling section on the userguide

                $error = new \SocialAverage\ErrorWrapper("Unspecified error.");

                switch ($e->getCode()) {
                    case 0 :
                        $error = new \SocialAverage\ErrorWrapper("Unspecified error.");
                        break;
                    case 1 :
                        $error = new \SocialAverage\ErrorWrapper("Hybridauth configuration error.");
                        break;
                    case 2 :
                        $error = new \SocialAverage\ErrorWrapper("Provider not properly configured.");
                        break;
                    case 3 :
                        $error = new \SocialAverage\ErrorWrapper("Unknown or disabled provider.");
                        break;
                    case 4 :
                        $error = new \SocialAverage\ErrorWrapper("Missing provider application credentials.");
                        break;
                    case 5 :
                        $error = new \SocialAverage\ErrorWrapper("Authentication failed. "
                            . "The user has canceled the authentication or the provider refused the connection.");
                        break;
                    case 6 :
                        $error = new \SocialAverage\ErrorWrapper("User profile request failed. Most likely the user is not connected "
                            . "to the provider and he should to authenticate again.");
                        $social->logout();
                        break;
                    case 7 :
                        $error = new \SocialAverage\ErrorWrapper("User not connected to the provider.");
                        $social->logout();
                        break;
                    case 8 :
                        $error = new \SocialAverage\ErrorWrapper("Provider does not support this feature.");
                        break;
                }

                $error->setOriginalMessage = $e->getMessage();
                $error->setOriginalStackTrace = $e->getTraceAsString();
                // well, basically your should not display this to the end user, just give him a hint and move on..
                echo "<br /><br /><b>Original error message:</b> " . $e->getMessage();
                echo "<hr /><h3>Trace</h3> <pre>" . $e->getTraceAsString() . "</pre>";

                return $error;

            }
        }

    }

    private function echoUserDetails($user_profile)
    {
        // access user profile data
        echo "Ohai there! U are connected with: <b>{$social->id}</b><br />";
        echo "As: <b>{$user_profile->displayName}</b><br />";
        echo "And your provider user identifier is: <b>{$user_profile->identifier}</b><br />";

        // or even inspect it
        echo "<pre>" . print_r($user_profile, true) . "</pre><br />";
    }


}