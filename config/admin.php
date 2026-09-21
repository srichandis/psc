<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Account
    |--------------------------------------------------------------------------
    |
    | Credentials for the seeded clinic staff account. Override these in your
    | .env file before seeding — particularly the password — and never commit
    | production values into source control.
    |
    */

    'name' => env('ADMIN_NAME', 'Clinic Reception'),

    'email' => env('ADMIN_EMAIL', 'admin@premierspecialistclinic.com.au'),

    'password' => env('ADMIN_PASSWORD', 'password'),

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Branding
    |--------------------------------------------------------------------------
    */

    'brand' => 'Premier Specialist Clinic',

    'title' => 'Clinic Admin',

];
