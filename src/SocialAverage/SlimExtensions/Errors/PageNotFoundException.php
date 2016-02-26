<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 29/01/2016
 * Time: 15:14
 */

namespace SocialAverage\SlimExtensions\Errors;

class PageNotFoundException extends HttpException
{
    public $url;
    public function __construct($url = "") {
        $message = 'Page not found.';
        $code = 404;
        $this->url = $url;
    }
}
