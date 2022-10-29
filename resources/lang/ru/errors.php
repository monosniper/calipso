<?php

return [
    'need_login' => 'Для начала необходимо войти в аккаунт',
    'login_or_reg' => 'Войдите или зарегистрируйтесь',
    'not_enough_money' => 'У вас недостаточно денег на балансе',
    'safe' => [
        'cant_close_without_result' => 'Нельзя завершить проект пока фрилансер не отправит результат',
    ],
    'lots' => [
        'no_archive' => 'У данного лота нет архива',
    ],
    'orders' => [
        'already_has_offer' => 'У этого заказа уже есть выбранный отклик',
    ],
    'limits' => [
        'active_lots' => 'Вы превысили максимальное количество активных лотов (макс. '.config('calipso.limits.active_lots').').',
        'active_orders' => 'Вы превысили максимальное количество активных заказов (макс. '.config('calipso.limits.active_orders').').',
    ]
];
