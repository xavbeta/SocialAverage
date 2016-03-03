<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 25/11/2015
 * Time: 17:05
 */

namespace SocialAverage\Socials\Sharer;

class TwitterSharer extends SocialSharer
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

        return '<a href="https://twitter.com/intent/tweet?text='.$encoded_url.'&hashtags=socialavg" '.$this->getOnClickSharerAttribute().' class="btn btn-social-icon btn-lg btn-twitter" ><span class="fa fa-twitter"></span></a>';

    }
}