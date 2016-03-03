<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 03/03/2016
 * Time: 13:12
 */

namespace SocialAverage\Socials\ProfileUrlBuilder;


class LinkedInProfileUrlBuilder implements SocialProfileUrlBuilder {

    public function build($account)
    {
        $account_data = json_decode($account->meta);

        return $account_data->profileURL;
    }
}
