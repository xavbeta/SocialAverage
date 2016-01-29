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
    public function __construct() {
        $message = 'Page not found.';
        $code = 404;
    }
}
