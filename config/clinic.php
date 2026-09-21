<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Clinic Identity
    |--------------------------------------------------------------------------
    |
    | Static practice information used across the public website and the
    | appointment confirmation emails/views. Anything that changes often
    | (specialists, specialties) lives in the database instead.
    |
    */

    'name' => 'Premier Specialist Clinic',
    'short_name' => 'PREMIER',
    'tagline' => 'Expert care for a healthier tomorrow',
    'slogan' => 'Specialist care with a personal approach',
    'script_signature' => 'People. Specialist care. Brighter tomorrows.',
    'description' => 'Expert medical care across neurology, psychiatry, psychology, endocrinology and nephrology in Coomera QLD.',

    /*
    |--------------------------------------------------------------------------
    | Contact Details
    |--------------------------------------------------------------------------
    */

    'address' => [
        'line1' => '4 Jowett Street',
        'line2' => 'Coomera QLD 4209',
        'full' => '4 Jowett Street, Coomera QLD 4209',
    ],

    'phone' => '(07) 5500 0536',
    'phone_link' => '0755000536',
    'fax' => '(07) 5500 0537',
    'email' => 'reception@premierspecialistclinic.com.au',

    'parking' => 'We offer free but limited parking for patients in our on-site carpark, including designated spaces for patients with disabilities. Additional street parking is also available along Jowett Street.',

    /*
    |--------------------------------------------------------------------------
    | Opening Hours
    |--------------------------------------------------------------------------
    */

    'hours' => [
        ['days' => 'Monday – Friday', 'times' => '8:30 am – 5:00 pm'],
        ['days' => 'Saturday & Sunday', 'times' => 'Closed'],
        ['days' => 'Public Holidays', 'times' => 'Closed'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Location
    |--------------------------------------------------------------------------
    */

    'map_embed_url' => 'https://maps.google.com/maps?q=4%20Jowett%20Street,%20Coomera%20QLD%204209&t=&z=16&ie=UTF8&iwloc=&output=embed',
    'directions_url' => 'https://maps.google.com/?q=4+Jowett+Street+Coomera+QLD+4209',

    /*
    |--------------------------------------------------------------------------
    | Appointment Rules
    |--------------------------------------------------------------------------
    |
    | The value below is surfaced in the booking form and on the confirmation
    | screen so patients understand Medicare referral requirements up front.
    |
    */

    'referral_notice' => 'A valid GP or specialist referral is required to receive Medicare rebates for specialist consultations.',

    'time_windows' => [
        'Morning (9:00am - 12:00pm)',
        'Afternoon (1:00pm - 5:00pm)',
        'Next available',
    ],

];
