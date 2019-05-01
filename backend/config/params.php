<?php
$scriptName = '/backend/web/index.php';
$frontendBaseUrl = str_replace($scriptName, '', $_SERVER['SCRIPT_NAME']).'/shop';
return [
    'frontendBaseUrl' => $frontendBaseUrl,
];
