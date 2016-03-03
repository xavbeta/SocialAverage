<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 27/01/2016
 * Time: 17:25
 */

namespace SocialAverage\Socials;


use InvalidArgumentException;
use ReflectionClass;

abstract class SocialNetwork extends BasicEnum
{
    const Facebook = 1;
    const Twitter = 2;
    const Instagram = 3;
    const Google = 4;
    const LinkedIn = 5;
    const OpenID = 6;
}

abstract class BasicEnum {
    private static $constCacheArray = NULL;

    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName($name, $strict = false) {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    public static function isValidValue($value, $strict = true) {
        $values = array_values(self::getConstants());
        return in_array($value, $values, $strict);
    }

    public static function NameToValue($name) {

        if(self::isValidName($name)) {
            $constants = self::getConstants();
            $keys = array_map('strtolower', array_keys($constants));
            $values = array_values($constants);
            $lower_constant = array_combine($keys, $values);

            return $lower_constant[strtolower($name)];
        } else {
            throw new InvalidArgumentException();
        }

    }

    public static function ValueToName($value) {
        if(self::isValidValue($value + 0)) {
            $constants = self::getConstants();
            $keys = array_map('strtolower', array_keys($constants));
            $values = array_values($constants);
            $lower_constant = array_combine($values, $keys);

            return $lower_constant[$value];
        } else {

            throw new InvalidArgumentException();
        }

    }

}