<?php
$common_url = '/lib';
$scriptName = [ '/frontend/web/index.php',
                '/backend/web/index.php'];
$common_url = str_replace($scriptName, '', $_SERVER['SCRIPT_NAME']).$common_url;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        //'@common-url' => '/lib',
	'@common-url' => $common_url,
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
