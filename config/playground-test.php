<?php

declare(strict_types=1);

return [
    'password' => env('PLAYGROUND_TEST_PASSWORD', 'password'),
    'password_encrypted' => (bool) env('PLAYGROUND_TEST_PASSWORD_ENCRYPTED', false),
    'users' => [
        'admin' => [
            'env' => 'TEST_EMAIL_ADMIN',
            'name' => 'Admin Nimda',
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
            'env' => 'TEST_EMAIL_CLIENT',
            'name' => 'Client Tneilc',
            'role' => 'client',
            'roles' => [],
            'description' => 'User: client',
            'status' => 1,
        ],
        'client-admin' => [
            'env' => 'TEST_EMAIL_CLIENT_ADMIN',
            'name' => 'Client Admin Nimda Tneilc',
            'role' => 'client',
            'roles' => [
                'client-admin',
            ],
            'description' => 'User: client admin',
            'status' => 1,
        ],
        'partner' => [
            'env' => 'TEST_EMAIL_PARTNER',
            'name' => 'Partner Rentrap',
            'role' => 'partner',
            'roles' => [],
            'description' => 'User: partner',
            'status' => 1,
        ],
        'partner-admin' => [
            'env' => 'TEST_EMAIL_PARTNER_ADMIN',
            'name' => 'Partner Admin Nimd Rentrap',
            'role' => 'partner',
            'roles' => [
                'partner-admin',
            ],
            'description' => 'User: partner admin',
            'status' => 1,
        ],
        'sales' => [
            'env' => 'TEST_EMAIL_SALES',
            'name' => 'Sales Selas',
            'role' => 'sales',
            'roles' => [
                'user',
            ],
            'description' => 'User: Sales',
            'status' => 1,
        ],
        'sales-admin' => [
            'env' => 'TEST_EMAIL_SALES_ADMIN',
            'role' => 'sales',
            'name' => 'Sales Admin Nimda Troppus',
            'roles' => [
                'user',
                'sales-admin',
            ],
            'description' => 'User: Sales Admin',
            'status' => 1,
        ],
        'support' => [
            'env' => 'TEST_EMAIL_SUPPORT',
            'name' => 'Support Troppus',
            'role' => 'support',
            'roles' => [
                'user',
            ],
            'description' => 'User: Support',
            'status' => 1,
        ],
        'support-admin' => [
            'env' => 'TEST_EMAIL_SUPPORT_ADMIN',
            'name' => 'Support Admin Nimda Troppus',
            'role' => 'support',
            'roles' => [
                'user',
                'support-admin',
            ],
            'description' => 'User: support admin',
            'status' => 1,
        ],
        'vendor' => [
            'env' => 'TEST_EMAIL_VENDOR',
            'name' => 'Vendor Rodnev',
            'role' => 'vendor',
            'roles' => [
                'user',
            ],
            'description' => 'Vendor',
            'status' => 1,
        ],
        'vendor-admin' => [
            'env' => 'TEST_EMAIL_VENDOR_ADMIN',
            'name' => 'Vendor Admin Nimda Rodnev',
            'role' => 'vendor',
            'roles' => [
                'user',
                'vendor-admin',
            ],
            'description' => 'User: vendor admin',
            'status' => 1,
        ],
        'manager' => [
            'env' => 'TEST_EMAIL_MANAGER',
            'name' => 'Manager Reganam',
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
            'env' => 'TEST_EMAIL_MANAGER_ADMIN',
            'name' => 'Manager Admin Nimda Reganam',
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
            'env' => 'TEST_EMAIL_WHEEL',
            'name' => 'Wheel Leehw',
            'role' => 'admin',
            'roles' => [
                'root',
            ],
            'description' => 'User: wheel',
            'status' => 1,
        ],
        'root' => [
            'env' => 'TEST_EMAIL_ROOT',
            'name' => 'Root Toor',
            'role' => 'root',
            'roles' => [],
            'description' => 'User: root',
            'status' => 1,
        ],
        'user' => [
            'env' => 'TEST_EMAIL_USER',
            'name' => 'User Resu',
            'role' => 'user',
            'roles' => [],
            'description' => 'User: user',
            'status' => 1,
        ],
        'user-admin' => [
            'env' => 'TEST_EMAIL_USER_ADMIN',
            'name' => 'User Admin Nimda Resu',
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
