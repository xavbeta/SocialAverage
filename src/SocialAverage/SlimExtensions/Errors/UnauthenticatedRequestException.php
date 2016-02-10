<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 15:22
 */

namespace SocialAverage\SlimExtensions\Errors;


class UnauthenticatedRequestException extends HttpException
{
    public function __construct() {
        $message = 'Unauthenticated';
        $code = 401;
    }
}
