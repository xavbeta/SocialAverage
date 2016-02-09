<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 29/01/2016
 * Time: 15:19
 */

namespace SocialAverage\SlimExtensions;

use SocialAverage\SlimExtensions\Errors\GenericException;
use SocialAverage\SlimExtensions\Errors\HttpException;

class ErrorHandler
{
    public static function Handle(\Exception $ex, $app)
    {
        if ($ex instanceof HttpException) {
            ErrorHandler::printError($ex, $app);
        } else {
            ErrorHandler::printError(new GenericException(), $app);
        }

    }

    private static function printError(HttpException $ex, $app)
    {

        // set the response content-type
        $app->response->headers->set('Content-Type', 'application/json');
        $app->response->setStatus($ex->getCode());

        $err = new \stdClass();
        $err->message = $ex->getMessage();
        $err->code = $ex->getCode();

        echo json_encode($err);
    }
}