<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 10/02/2016
 * Time: 13:04
 */

use SocialAverage\SlimExtensions\ErrorHandler;

$app->error(function (\Exception $e) use ($app) {
    if($e instanceof \SocialAverage\SlimExtensions\Errors\HttpException){
        $eh = new ErrorHandler();
        $eh->Handle($e, $app);
    } else {

    }
});