<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 02/02/2016
 * Time: 14:58
 */

namespace SocialAverage\Inputs;


use SocialAverage\SlimExtensions\Errors\BadRequestException;

class DataPostExtractor
{

    private static function ExtractField($request, $fieldLabel){
        try {
            return $request->post($fieldLabel);
        } catch(\Exception $exception){
            throw new BadRequestException();
        }
    }

    public static function ExtractNodeId($request){
        return DataPostExtractor::ExtractField($request, 'node');
    }

    public static function ExtractTokenId($request)
    {
        return DataPostExtractor::ExtractField($request, 'token');
    }

    public static function ExtractSocial($request)
    {
        return DataPostExtractor::ExtractField($request, 'social');
    }

    public static function ExtractAccountIdentifier($request)
    {
        return DataPostExtractor::ExtractField($request, 'social-identifier');
    }

    public static function ExtractAccountDisplayName($request)
    {
        return DataPostExtractor::ExtractField($request, 'social-display-name');
    }

    public static function ExtractAccountPhotoUrl($request)
    {
        return DataPostExtractor::ExtractField($request, 'social-photo-url');
    }

    public static function ExtractAccountMeta($request)
    {
        return DataPostExtractor::ExtractField($request, 'social-meta');
    }

}