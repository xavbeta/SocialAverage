<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 03/03/2016
 * Time: 13:12
 */

namespace SocialAverage\Socials\ProfileUrlBuilder;


class InstagramProfileUrlBuilder implements SocialProfileUrlBuilder {

    public function build($account)
    {
        $account_data = json_decode($account->meta);

        return "https://www.instagram.com/".$account_data->username."/";
    }
}
