<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 13/01/2016
 * Time: 11:48
 */

namespace SocialAverage\Config;


class Configuration
{

    private static $default_DbHost = "193.204.198.5";
    private static $default_DbName = "collective_intelligence";
    private static $default_DbUsername = "postgres";
    private static $default_DbPassword = "saverio";

    private $DbHost;
    private $DbName;
    private $DbUsername;
    private $DbPassword;

    function __construct($DbHost, $DbName, $DbUser, $DbPass) {
        $this->DbHost = $DbHost;
        $this->DbName = $DbName;
        $this->DbUsername = $DbUser;
        $this->DbPassword = $DbPass;
    }

    public static function getDefaultConfiguration() {
        return new Configuration(Configuration::$default_DbHost,
            Configuration::$default_DbName,
            Configuration::$default_DbUsername,
            Configuration::$default_DbPassword);
    }

    public function getDbHost()
    {
        return $this->DbHost;
    }

    public function getDbName()
    {
        return $this->DbName;
    }

    public function getDbUsername()
    {
        return $this->DbUsername;
    }

    public function getDbPassword()
    {
        return $this->DbPassword;
    }
}