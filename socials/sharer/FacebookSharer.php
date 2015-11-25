<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 25/11/2015
 * Time: 17:05
 */

namespace SocialAverage;


class FacebookSharer implements SocialSharer
{

    /**
     * Get the current Url taking into account Https and Port
     * @link http://css-tricks.com/snippets/php/get-current-page-url/
     * @version Refactored by @AlexParraSilva
     */
    private function getUrl() {
        $url  = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
        $url .= '://' . $_SERVER['SERVER_NAME'];
        $url .= in_array( $_SERVER['SERVER_PORT'], array('80', '443') ) ? '' : ':' . $_SERVER['SERVER_PORT'];
        $url .= $_SERVER['REQUEST_URI'];
        return $url;
    }

    /**
     * Provide HTML code customized with provided url.
     * @param $url
     * @param $text
     * @return string
     */
    public function getSharerCode($url = "", $text = "")
    {
        if($url === ""){
            $url = $this->getUrl();
        }

        $encoded_url = urlencode($url);

        return '<a href="https://www.facebook.com/sharer/sharer.php?u='.$encoded_url.'" target="_blank" >'.$text.'</a>';


    }
}