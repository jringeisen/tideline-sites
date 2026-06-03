<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Company / Brand Identity
    |--------------------------------------------------------------------------
    |
    | Single source of truth for the brand name and contact details that are
    | repeated across marketing pages, structured data, transactional emails,
    | and the Inertia-driven app shell. Override per-environment via .env.
    |
    */

    'name' => env('COMPANY_NAME', env('APP_NAME', 'All American Web Design')),

    'email' => env('COMPANY_EMAIL', 'hello@allamericanwebdesign.com'),

];
