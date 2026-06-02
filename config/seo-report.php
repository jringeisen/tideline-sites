<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Model
    |--------------------------------------------------------------------------
    |
    | The OpenAI model used to generate SEO reports. Swap via the
    | SEO_REPORT_MODEL environment variable without touching code.
    |
    */

    'model' => env('SEO_REPORT_MODEL', 'gpt-4o-mini'),

    /*
    |--------------------------------------------------------------------------
    | Industries
    |--------------------------------------------------------------------------
    |
    | The allow-list of industries a guest may choose. Used to validate the
    | submission, populate the front-end <select>, and tailor the AI prompt.
    |
    */

    'industries' => [
        'Restaurant / Food',
        'Home Services',
        'Retail',
        'Professional Services',
        'Healthcare',
        'Real Estate',
        'Automotive',
        'Beauty & Wellness',
        'Legal',
        'Construction',
        'Other',
    ],

    /*
    |--------------------------------------------------------------------------
    | Website Fetch Limits
    |--------------------------------------------------------------------------
    |
    | Guards for fetching arbitrary, user-supplied URLs. Keep timeouts tight
    | and cap the response size to avoid tying up workers or large-body abuse.
    |
    */

    'fetch' => [
        'timeout' => 8,
        'connect_timeout' => 5,
        'max_bytes' => 2_000_000,
        'max_redirects' => 3,
        'user_agent' => 'AllAmericanWebDesignSeoBot/1.0 (+https://allamericanwebdesign.com)',
    ],

];
