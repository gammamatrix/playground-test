<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Credentials for testing
    |--------------------------------------------------------------------------
    |
    | If the password is empty, a random value will be set.
    |
    | For extra security, a password hash may be set in
    | PLAYGROUND_TEST_PASSWORD; and then PLAYGROUND_TEST_PASSWORD_ENCRYPTED must
    | be set to true.
    |
    */

    'password' => env('PLAYGROUND_TEST_PASSWORD', ''),

    'password_encrypted' => (bool) env('PLAYGROUND_TEST_PASSWORD_ENCRYPTED', false),

    /*
    |--------------------------------------------------------------------------
    | Loading
    |--------------------------------------------------------------------------
    |
    | PLAYGROUND_TEST_LOAD_TRANSLATIONS loads translations in /lang
    |
*/

    'load' => [
        'translations' => (bool) env('PLAYGROUND_TEST_LOAD_TRANSLATIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | Test with migrations enabled.
    |
    */

    'db' => [
        'migrations' => (bool) env('PLAYGROUND_TEST_DB_MIGRATIONS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles and Privileges
    |--------------------------------------------------------------------------
    |
    | with_role: users.role string
    | with_roles: users.roles role[]
    | with_privileges: users.privileges privilege[]
    |
    | All privileges: ['*']
    |
    */

    'with_active' => (bool) env('PLAYGROUND_TEST_WITH_ACTIVE', true),

    'with_description' => (bool) env('PLAYGROUND_TEST_WITH_DESCRIPTION', true),

    'with_privileges' => (bool) env('PLAYGROUND_TEST_WITH_PRIVILEGES', true),

    'with_role' => (bool) env('PLAYGROUND_TEST_WITH_ROLE', true),

    'with_roles' => (bool) env('PLAYGROUND_TEST_WITH_ROLES', true),

    'with_status' => (bool) env('PLAYGROUND_TEST_WITH_STATUS', true),

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    |
    |
    */

    'users' => [
        'admin' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_ADMIN'),
            'name' => 'Admin Nimda',
            'privileges' => [],
            'role' => 'admin',
            'roles' => [
                'user',
                'publisher',
                'sales',
            ],
            'description' => 'User: admin',
            'status' => 1,
        ],
        'client' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_CLIENT'),
            'name' => 'Client Tneilc',
            'privileges' => [],
            'role' => 'client',
            'roles' => [],
            'description' => 'User: client',
            'status' => 1,
        ],
        'client-admin' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_CLIENT_ADMIN'),
            'name' => 'Client Admin Nimda Tneilc',
            'privileges' => [],
            'role' => 'client',
            'roles' => [
                'client-admin',
            ],
            'description' => 'User: client admin',
            'status' => 1,
        ],
        'partner' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_PARTNER'),
            'name' => 'Partner Rentrap',
            'privileges' => [],
            'role' => 'partner',
            'roles' => [],
            'description' => 'User: partner',
            'status' => 1,
        ],
        'partner-admin' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_PARTNER_ADMIN'),
            'name' => 'Partner Admin Nimd Rentrap',
            'privileges' => [],
            'role' => 'partner',
            'roles' => [
                'partner-admin',
            ],
            'description' => 'User: partner admin',
            'status' => 1,
        ],
        'sales' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_SALES'),
            'name' => 'Sales Selas',
            'privileges' => [],
            'role' => 'sales',
            'roles' => [
                'user',
            ],
            'description' => 'User: Sales',
            'status' => 1,
        ],
        'sales-admin' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_SALES_ADMIN'),
            'role' => 'sales',
            'name' => 'Sales Admin Nimda Troppus',
            'privileges' => [],
            'roles' => [
                'user',
                'sales-admin',
            ],
            'description' => 'User: Sales Admin',
            'status' => 1,
        ],
        'support' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_SUPPORT'),
            'name' => 'Support Troppus',
            'privileges' => [],
            'role' => 'support',
            'roles' => [
                'user',
            ],
            'description' => 'User: Support',
            'status' => 1,
        ],
        'support-admin' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_SUPPORT_ADMIN'),
            'name' => 'Support Admin Nimda Troppus',
            'privileges' => [],
            'role' => 'support',
            'roles' => [
                'user',
                'support-admin',
            ],
            'description' => 'User: support admin',
            'status' => 1,
        ],
        'vendor' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_VENDOR'),
            'name' => 'Vendor Rodnev',
            'privileges' => [],
            'role' => 'vendor',
            'roles' => [
                'user',
            ],
            'description' => 'Vendor',
            'status' => 1,
        ],
        'vendor-admin' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_VENDOR_ADMIN'),
            'name' => 'Vendor Admin Nimda Rodnev',
            'privileges' => [],
            'role' => 'vendor',
            'roles' => [
                'user',
                'vendor-admin',
            ],
            'description' => 'User: vendor admin',
            'status' => 1,
        ],
        'manager' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_MANAGER'),
            'name' => 'Manager Reganam',
            'privileges' => [],
            'role' => 'manager',
            'roles' => [
                'user',
                'publisher',
                'sales',
            ],
            'description' => 'User: manager',
            'status' => 1,
        ],
        'manager-admin' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_MANAGER_ADMIN'),
            'name' => 'Manager Admin Nimda Reganam',
            'privileges' => [],
            'role' => 'manager',
            'roles' => [
                'user',
                'manager-admin',
                'publisher',
                'sales',
            ],
            'description' => 'User: manager admin',
            'status' => 1,
        ],
        'wheel' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_WHEEL'),
            'name' => 'Wheel Leehw',
            'privileges' => [],
            'role' => 'admin',
            'roles' => [
                'root',
            ],
            'description' => 'User: wheel',
            'status' => 1,
        ],
        'root' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_ROOT'),
            'name' => 'Root Toor',
            'privileges' => [
                '*',
            ],
            'role' => 'root',
            'roles' => [],
            'description' => 'User: root',
            'status' => 1,
        ],
        'user' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_USER'),
            'name' => 'User Resu',
            'privileges' => [],
            'role' => 'user',
            'roles' => [],
            'description' => 'User: user',
            'status' => 1,
        ],
        'user-admin' => [
            'email' => env('PLAYGROUD_TEST_EMAIL_USER_ADMIN'),
            'name' => 'User Admin Nimda Resu',
            'privileges' => [],
            'role' => 'user',
            'roles' => [
                'user',
                'user-admin',
            ],
            'description' => 'User: user admin',
            'status' => 1,
        ],
    ],
];
