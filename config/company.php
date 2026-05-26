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

    // E.164-style number used in tel: links and schema.org telephone fields.
    'phone' => env('COMPANY_PHONE', '+1-850-684-8924'),

    // Human-friendly number shown to visitors.
    'phone_display' => env('COMPANY_PHONE_DISPLAY', '(850) 684-8924'),

];
