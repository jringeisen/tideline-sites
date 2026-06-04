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

    /*
    | Phone number for the site-wide NAP and structured data. Left null until a
    | real business line exists, so nothing renders a placeholder. Store the
    | display form (e.g. "(850) 555-0123") in COMPANY_PHONE; the tel: link is
    | derived by stripping non-digits.
    */
    'phone' => env('COMPANY_PHONE'),

    /*
    | Locality / region used by the NAP and LocalBusiness schema. Street is
    | optional — set COMPANY_STREET only if a full postal address is wanted.
    */
    'street' => env('COMPANY_STREET'),

    'locality' => env('COMPANY_LOCALITY', 'Panama City Beach'),

    'region' => env('COMPANY_REGION', 'FL'),

    'postal_code' => env('COMPANY_POSTAL_CODE'),

    /*
    | Public profiles for schema `sameAs`. Add any that exist; blanks are
    | filtered out before rendering.
    */
    'social' => array_values(array_filter([
        env('COMPANY_GBP_URL'),
        env('COMPANY_FACEBOOK_URL'),
        env('COMPANY_INSTAGRAM_URL'),
        env('COMPANY_LINKEDIN_URL'),
    ])),

];
