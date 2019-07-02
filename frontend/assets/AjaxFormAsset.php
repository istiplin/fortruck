<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AjaxFormAsset extends AssetBundle
{
    public $basePath = '@frontend';
    public $baseUrl = '@frontend-url';
    
    //public $basePath = '@webroot';
    //public $baseUrl = '@web';
    
    public $js = [
	'js/ajax-form.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}