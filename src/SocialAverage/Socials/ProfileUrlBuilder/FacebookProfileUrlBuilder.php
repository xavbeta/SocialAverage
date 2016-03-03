<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 03/03/2016
 * Time: 13:12
 */

namespace SocialAverage\Socials\ProfileUrlBuilder;


class FacebookProfileUrlBuilder implements SocialProfileUrlBuilder {

    public function build($account)
    {
        return "https://facebook.com/".$account->identifier;
    }
}
