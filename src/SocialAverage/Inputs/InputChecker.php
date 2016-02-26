<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 28/01/2016
 * Time: 11:48
 */

namespace SocialAverage\Inputs;


use SocialAverage\Nodes\NodeManager;
use Webpatser\Uuid\Uuid;
use SocialAverage\Socials\SocialNetwork;

class InputChecker
{

    public static function CheckTokenId($token_id)
    {
        $uuid = Uuid::import($token_id);

        return !($uuid == null || $uuid == false || $uuid->version == null);
    }


    public static function CheckNodeId($nodeId, $checkAgainstDB = false){
        if(is_numeric($nodeId)){
            if($checkAgainstDB){
                $nm = NodeManager::GetInstance();
                if($nm->GetNode($nodeId)){
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    public static function CheckSocial($social)
    {
        return SocialNetwork::isValidValue($social);
    }

    public static function CheckAccountIdentifier($username)
    {
        return !is_null($username)
            && is_string($username)
            && strlen(trim($username)) > 0;
    }

    public static function CheckAccountPhotoUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public static function CheckAccountDisplayName($username)
    {
        return !is_null($username)
        && is_string($username)
        && strlen(trim($username)) > 0;
    }

    public static function CheckAccountMeta($meta)
    {
        return true;
    }
}