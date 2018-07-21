<?php

namespace common\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AjaxFormAsset extends AssetBundle
{
    public $basePath = '@common';
    public $baseUrl = '@common-url';
    
    public $js = [
	'js/ajax-form.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}