<?php
$scriptName = '/frontend/web/index.php';
$backendBaseUrl = str_replace($scriptName, '', $_SERVER['SCRIPT_NAME']).'/admin';
return [
    'backendBaseUrl' => $backendBaseUrl,
];
