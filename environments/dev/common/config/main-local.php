<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=shop',
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8',
        ],
        'mailer' => [
            //'class' => 'yii\swiftmailer\Mailer',
            'class' => 'common\components\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            /*
            'messageConfig' => [
                'from' => ['istiplin@gmail.com' => 'ForTruck'],
            ],
             * 
             */
            //'transport' => [
                //'class' => 'Swift_SmtpTransport',
                //'host' => 'smtp.gmail.com',
                //'username' => 'istiplin@gmail.com',
                //'password' => 'jtzasonhmfrrtzta',
                //
                //'password' => 'wjfqiukdmqypdkdm',//mail.ru
                //'port' => '587',
                //'encryption' => 'tls',
            //],
            
            'useFileTransport' => false,
        ],
    ],
];
