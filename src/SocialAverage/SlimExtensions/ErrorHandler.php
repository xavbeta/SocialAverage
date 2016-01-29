<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 29/01/2016
 * Time: 15:19
 */

namespace SocialAverage\SlimExtensions;

use SocialAverage\SlimExtensions\Errors\HttpException;

class ErrorHandler
{
    public static function Handle(HttpException $ex, $app){
            $app->response->setStatus($ex->getCode());
            $app->response->setBody($ex->getMessage());
    }
}