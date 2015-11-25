<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 25/11/2015
 * Time: 17:05
 */

namespace SocialAverage\Socials\Sharer;

class MailSharer extends SocialSharer
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

        return '<a href="mailto:?&subject=Play with us&body=that\'s%20my%20value%3A'.$encoded_url.'" '.$this->getOnClickSharerAttribute().'>'.$text.'</a>';
    }
}