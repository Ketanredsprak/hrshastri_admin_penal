<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Form 16 module UI
    |--------------------------------------------------------------------------
    | Controls sidebar + Employee List Form 16 button only.
    | Routes remain available for direct URL / internal testing.
    | Set FORM16_ENABLED=true in .env to show the UI again.
    */
    'enabled' => (bool) env('FORM16_ENABLED', false),
];
