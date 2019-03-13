<?php
$scriptName = '/backend/web/index.php';
$baseUrl = str_replace($scriptName, '', $_SERVER['SCRIPT_NAME']).'/admin';

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name'=>'�������',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => $baseUrl,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            //'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true],
        ],
        /*
        'session' => [
            // this is the name of the session cookie used for login on the backend
            //'name' => 'advanced-backend',
            'name' => 'advanced',
        ],
         * 
         */
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' =>  [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
				'' => 'site/index',
                                'login' => 'site/login',
                                'logout' => 'site/logout',
				'<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
			],
		],
        'urlManagerFrontend' => [
            'class'=>'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
				'' => 'site/index',                                
				'<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
        
    ],
    'params' => $params,
];
