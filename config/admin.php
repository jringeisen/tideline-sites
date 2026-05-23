<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Emails
    |--------------------------------------------------------------------------
    |
    | A comma-separated list of email addresses (defined in the ADMIN_EMAILS
    | environment variable) granted admin access. The User model's `is_admin`
    | accessor checks membership against this list.
    |
    */

    'emails' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('ADMIN_EMAILS', ''))
    ))),

];
