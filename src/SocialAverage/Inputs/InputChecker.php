<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 28/01/2016
 * Time: 11:48
 */

namespace SocialAverage\Inputs;


use Webpatser\Uuid\Uuid;

class InputChecker
{

    public static function CheckTokenId($token_id)
    {
        $uuid = Uuid::import($token_id);

        return !($uuid == null || $uuid == false || $uuid->version == null);
    }
}