<?php
/**
 * Created by PhpStorm.
 * User: Saverio Delpriori (saverio.delpriori@gmail.com)
 * Date: 24/11/2015
 * Time: 09:53
 */

return
    array(
        "base_url" => "http://www.s-delpriori.it/socialaverage/login/",
        "providers" => array(
            "Twitter" => array(
                "enabled" => true, "keys" => array("key" => "sYm6ovnDV02uCgLSmvhxuwQQt", "secret" => "drhUIU9UX7brlv7dKyAGJn9y4HYZcbj1YA5tv89R9dpYWXjihf"),
                "includeEmail" => true
            ),
            "Facebook" => array(
                "enabled" => true,
                "keys" => array("id" => "471722693029141", "secret" => "377a297144a45e4ad274c6bad53366e5"),
                "scope" => "email, user_about_me"
            ),
            "OpenID" => array(
                "enabled" => true
            ),
            "Yahoo" => array(
                "enabled" => true,
                "keys" => array("key" => "", "secret" => ""),
            ),
            "AOL" => array(
                "enabled" => true
            ),
            "Google" => array(
                "enabled" => true,
                "keys" => array("id" => "529336150163-otkm6umvl7epai9evkmuj5tiu4uur1tq.apps.googleusercontent.com", "secret" => "6t9FXRDaWbxYz2z7lhj-AXw_"),
            ),
            // windows live
            "Live" => array(
                "enabled" => true,
                "keys" => array("id" => "", "secret" => "")
            ),
            "LinkedIn" => array(
                "enabled" => true,
                "keys" => array("key" => "77c7658i7h6wc8", "secret" => "jqMdx8UmzqekWJrW")
            ),

            "Instagram" => array(
                "enabled" => true,
                "keys" => array("id" => "bb499fc8695f4b0e910795c12c4a1a2e", "secret" => "c00aad5542294174ae102131d394140a")
            ),
        ),
        // If you want to enable logging, set 'debug_mode' to true.
        // You can also set it to
        // - "error" To log only error messages. Useful in production
        // - "info" To log info and error messages (ignore debug messages)
        "debug_mode" => false,
        // Path to file writable by the web server. Required if 'debug_mode' is not false
        "debug_file" => "",
    );
