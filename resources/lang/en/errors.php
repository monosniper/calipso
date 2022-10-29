<?php

return [
    'need_login' => 'First you need to log in to your account',
    'login_or_reg' => 'Log in or register',
    'not_enough_money' => 'You don\'t have enough money on your balance sheet',
    'safe' => [
        'cant_close_without_result' => 'You cannot complete the project until the freelancer sends the result',
    ],
    'lots' => [
        'no_archive' => 'This lot does not have an archive',
    ],
    'orders' => [
        'already_has_offer' => 'This order already has a selected response',
    ],
    'limits' => [
        'active_lots' => 'You have exceeded the maximum number of active lots (max. '.config('calipso.limits.active_lots').').',
        'active_orders' => 'You have exceeded the maximum number of active orders (макс. '.config('calipso.limits.active_orders').').',
    ]
];
