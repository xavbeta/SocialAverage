<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 17:45
 */

namespace SocialAverage;

require_once "socials/sharer/TwitterSharer.php";
require_once "socials/sharer/FacebookSharer.php";
require_once "socials/sharer/GoogleSharer.php";
require_once "socials/sharer/LinkedInSharer.php";
require_once "socials/sharer/MailSharer.php";

class SocialSharerTemplate
{
    public static function getFacebookSharer($text = "", $url = "") {
        $sharer = new FacebookSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getTwitterSharer($text = "", $url = "") {
        $sharer = new TwitterSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getGoogleSharer($text = "", $url = "") {
        $sharer = new GoogleSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getLinkedInSharer($text = "", $url = "") {
        $sharer = new LinkedInSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getMailSharer($text = "", $url = "") {
        $sharer = new MailSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getAllSharerTemplate($text = "",$url = "") {
        return "<ul>\n".
            "<li>".static::getTwitterSharer($text, $url)."</li>\n".
            "<li>".static::getFacebookSharer($text, $url)."</li>\n".
            "<li>".static::getGoogleSharer($text, $url)."</li>\n".
            "<li>".static::getLinkedInSharer($text, $url)."</li>\n".
            "<li>".static::getMailSharer($text, $url)."</li>\n".
        "</ul>";
    }
}