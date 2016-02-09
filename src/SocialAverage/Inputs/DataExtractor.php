<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 02/02/2016
 * Time: 14:58
 */

namespace SocialAverage\Inputs;


use SocialAverage\SlimExtensions\Errors\BadRequestException;

class DataExtractor
{

    private static function ExtractField($body, $fieldLabel){
        try {
            $body = json_decode($body, true);
            return $body[$fieldLabel];
        } catch(\Exception $exception){
            throw new BadRequestException();
        }
    }

    public static function ExtractNodeId($body){
        return DataExtractor::ExtractField($body, 'node');
    }

    public static function ExtractTokenId($body)
    {
        return DataExtractor::ExtractField($body, 'token');
    }

}