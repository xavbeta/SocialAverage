<?php
/**
 * Created by PhpStorm.
 * User: SAVERI0
 * Date: 29/02/2016
 * Time: 14:43
 */


function siteURL()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'].'/';
    return $protocol.$domainName;
}
DEFINE( 'SITE_URL', siteURL() );