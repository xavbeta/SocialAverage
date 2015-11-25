<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 24/11/2015
 * Time: 13:36
 */

namespace SocialAverage;

require_once ("ILoginWrapper.php");

class GoogleLoginWrapper extends  ILoginWrapper
{
    protected function doLogin(\Hybrid_Auth $hybridauth)
    {
        return $hybridauth->authenticate("Google");
    }

    protected function getCallbackURL($base_path)
    {
        return $base_path."google";
    }
}