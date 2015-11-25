<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 24/11/2015
 * Time: 13:36
 */

namespace SocialAverage;

require_once ("ILoginWrapper.php");

class OpenIDLoginWrapper extends  ILoginWrapper
{
    protected function doLogin(\Hybrid_Auth $hybridauth)
    {
        return $hybridauth->authenticate("OpenID", array( "openid_identifier" => "https://me.yahoo.com/"));
    }

    protected function getCallbackURL($base_path)
    {
        return $base_path."openid";
    }
}