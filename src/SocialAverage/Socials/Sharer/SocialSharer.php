<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 25/11/2015
 * Time: 17:03
 */

namespace SocialAverage\Socials\Sharer;


abstract class SocialSharer
{

    /**
     * Get the current Url taking into account Https and Port
     * @link http://css-tricks.com/snippets/php/get-current-page-url/
     * @version Refactored by @AlexParraSilva
     */
    protected function getUrl() {
        $url  = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https' : 'http';
        $url .= '://' . $_SERVER['SERVER_NAME'];
        $url .= in_array( $_SERVER['SERVER_PORT'], array('80', '443') ) ? '' : ':' . $_SERVER['SERVER_PORT'];
        $url .= $_SERVER['REQUEST_URI'];
        return $url;
    }


    protected function getOnClickSharerAttribute(){
        return 'onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;" ';
    }

    /**
     * Provide HTML code customized with provided url.
     * @param $text
     * @param $url
     * @return string
     */
    abstract public function getSharerCode($text = "", $url = "");
}