<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 17:45
 */

namespace SocialAverage\Templates;


class SocialSharerTemplate
{
    public static function getFacebookSharer($text = "", $url = "") {
        $sharer = new \SocialAverage\Socials\Sharer\FacebookSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getTwitterSharer($text = "", $url = "") {
        $sharer = new \SocialAverage\Socials\Sharer\TwitterSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getGoogleSharer($text = "", $url = "") {
        $sharer = new \SocialAverage\Socials\Sharer\GoogleSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getLinkedInSharer($text = "", $url = "") {
        $sharer = new \SocialAverage\Socials\Sharer\LinkedInSharer();
        return $sharer->getSharerCode($text, $url);
    }

    public static function getMailSharer($text = "", $url = "") {
        $sharer = new \SocialAverage\Socials\Sharer\MailSharer();
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