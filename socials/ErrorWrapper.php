<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 24/11/2015
 * Time: 16:23
 */

namespace SocialAverage;


class ErrorWrapper
{

    public $message;
    public $original_message;
    public $original_stacktrace;

    function __construct($message = ""){
        $this->message = $message;
    }

}