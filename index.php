<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 23/11/2015
 * Time: 15:39
 */

// Autoload
require 'vendor/autoload.php';

// Instantiate a Slim application
$app = new \Slim\Slim(array(
    'debug' => true
));

$app->get('/', function () {
    echo "Hello, user!";
});

// Run the Slim application
$app->run();

