<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/bases.css',
        //'css/signin.css',
        //'css/site.css',
        'css/head.css',
        'css/styles_buttons.css',
        'css/style.css',
        
    ];
    public $js = [
        'js/script.js',
        //'js/jquery-3.3.1.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
