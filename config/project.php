<?php

/*
Use this file for project configuration extensions.
*/
return [
    /*
    |--------------------------------------------------------------------------
    | Application email_logo name
    |--------------------------------------------------------------------------
    | You can user different logos in email templates. Just replace this config key via env(...),
    |
    */
    'email_logo' => 'starter-kit.png',

    /*
    |--------------------------------------------------------------------------
    | Trusted proxy IP
    |--------------------------------------------------------------------------
    |
    */

    'trusted_proxy_ip' => env('TRUSTED_PROXY_IP'),

    /*
    |--------------------------------------------------------------------------
    | AWS ELB Enabled
    |--------------------------------------------------------------------------
    |
    */

    'aws_elb_enabled' => env('AWS_ELB_ENABLED', false),
];