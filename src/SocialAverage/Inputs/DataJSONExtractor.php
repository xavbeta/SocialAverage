<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 02/02/2016
 * Time: 14:58
 */

namespace SocialAverage\Inputs;


use SocialAverage\SlimExtensions\Errors\BadRequestException;

class DataJSONExtractor
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
        return DataJSONExtractor::ExtractField($body, 'node');
    }

    public static function ExtractTokenId($body)
    {
        return DataJSONExtractor::ExtractField($body, 'token');
    }

    public static function ExtractSocial($body)
    {
        return DataJSONExtractor::ExtractField($body, 'social');
    }

    public static function ExtractAccountIdentifier($body)
    {
        return DataJSONExtractor::ExtractField($body, 'social-identifier');
    }

    public static function ExtractAccountDisplayName($body)
    {
        return DataJSONExtractor::ExtractField($body, 'social-display-name');
    }

    public static function ExtractAccountPhotoUrl($body)
    {
        return DataJSONExtractor::ExtractField($body, 'social-photo-url');
    }

    public static function ExtractAccountMeta($body)
    {
        return DataJSONExtractor::ExtractField($body, 'social-meta');
    }

}