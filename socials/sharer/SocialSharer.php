<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 25/11/2015
 * Time: 17:03
 */

namespace SocialAverage;


interface SocialSharer
{


    /**
     * Provide HTML code customized with provided url.
     * @param $url
     * @param $text
     * @return string
     */
    public function getSharerCode($url = "", $text = "");
}