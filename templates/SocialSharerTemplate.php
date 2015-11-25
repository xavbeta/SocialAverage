<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 17:45
 */

namespace SocialAverage;


use \SocialAverage\FacebookSharer;

class SocialSharerTemplate
{
    public static function getFacebookSharer($url) {
        $sharer = new FacebookSharer();
        return $sharer->getSharerCode();
    }

    public static function getAllSharerTemplate($url) {
        ?>
        
        <?php
    }
}