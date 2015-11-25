<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 24/11/2015
 * Time: 13:36
 */

namespace SocialAverage\Socials;


class LinkedInLoginWrapper extends  ILoginWrapper
{
    protected function doLogin(\Hybrid_Auth $hybridauth)
    {
        return $hybridauth->authenticate("LinkedIn");
    }

    protected function getCallbackURL($base_path)
    {
        return $base_path."linkedin";
    }
}