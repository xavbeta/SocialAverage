<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 25/11/2015
 * Time: 21:33
 */

$loader = require 'vendor/autoload.php';
$loader->add('SocialAverage\\', __DIR__.'/src/');


$c = new \SocialAverage\Socials\Sharer\TwitterSharer();

var_dump($c);

$b = \SocialAverage\Templates\SocialSharerTemplate::getFacebookSharer("#");
var_dump($b);
