<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 29/01/2016
 * Time: 15:14
 */

namespace SocialAverage\SlimExtensions\Errors;


class IllegalRequestException extends HttpException
{
    public $message;
    public $code;

    public function __construct() {
        $this->message = 'Illegal request.';
        $this->code = 403;
    }
}
