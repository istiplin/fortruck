<?php
$common_url = '/lib';
$frontend_url = '/shop';

$scriptName = ['/frontend/web/index.php','/backend/web/index.php'];
$baseUrl = str_replace($scriptName, '', $_SERVER['SCRIPT_NAME']);

$common_url = $baseUrl.$common_url;
$frontend_url = $baseUrl.$frontend_url;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
	'@common-url' => $common_url,
        '@frontend-url' => $frontend_url
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
