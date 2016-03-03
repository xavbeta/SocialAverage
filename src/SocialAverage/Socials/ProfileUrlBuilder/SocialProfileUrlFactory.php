<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 03/03/2016
 * Time: 13:15
 */

namespace SocialAverage\Socials\ProfileUrlBuilder;


use SocialAverage\Socials\SocialNetwork;

class SocialProfileUrlFactory
{

    public static function GetProfileUrl($account){
        $pf = null;
        switch($account->social_id){
            case SocialNetwork::Facebook:
                $pf = new FacebookProfileUrlBuilder();
                break;
            case SocialNetwork::Twitter:
                $pf = new TwitterProfileUrlBuilder();
                break;
            case SocialNetwork::LinkedIn:
                $pf = new LinkedInProfileUrlBuilder();
                break;
            case SocialNetwork::Google:
                $pf = new GoogleProfileUrlBuilder();
                break;
            case SocialNetwork::Instagram:
                $pf = new InstagramProfileUrlBuilder();
                break;
            case SocialNetwork::OpenID:
                $pf = new OpenIDProfileUrlBuilder();
                break;
        }


        return $pf == null ? "" : $pf->build($account);
    }

}