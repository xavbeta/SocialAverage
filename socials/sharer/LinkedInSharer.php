<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 25/11/2015
 * Time: 17:05
 */

namespace SocialAverage;

require_once "SocialSharer.php";

class LinkedInSharer extends SocialSharer
{

    /**
     * Provide HTML code customized with provided url.
     * @param $text
     * @param $url
     * @return string
     */
    public function getSharerCode($text = "",$url = "")
    {
        if($url === ""){
            $url = $this->getUrl();
        }

        $encoded_url = urlencode($url);

        return '<a href="https://www.linkedin.com/shareArticle?mini=true&url='.$encoded_url.'&title='.$text.'&summary=&source=" '.$this->getOnClickSharerAttribute().'>'.$text.'</a>';

    }
}