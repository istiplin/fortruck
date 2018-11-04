<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=tominaa_shop',
            'username' => 'tominaa_shop',
            'password' => '123456',
			//'dsn' => 'mysql:host=localhost;dbname=shop',
            //'username' => 'root',
            //'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            //'class' => 'yii\swiftmailer\Mailer',
            'class' => 'common\components\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
