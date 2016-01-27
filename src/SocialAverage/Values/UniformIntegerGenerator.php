<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 27/01/2016
 * Time: 17:13
 */

namespace SocialAverage\Values;


class UniformIntegerGenerator implements IValueGenerator
{
    private static $Max = 100;

    public function Generate()
    {
        return mt_rand(0,UniformIntegerGenerator::$Max);
    }
}