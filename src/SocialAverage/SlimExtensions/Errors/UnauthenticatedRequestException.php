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
    public $resourceUri = "";
    public function __construct($resourceUri) {
        $message = 'Unauthenticated';
        $code = 401;
        $this->resourceUri = $resourceUri;
    }
}
