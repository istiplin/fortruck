<?php
namespace common\components;

use common\models\Config;

class Application extends \yii\web\Application
{
    public function init()
    {
        parent::init();
        //заранее подгружаем настройки
        Config::getValuesAll();
    }
}
?>
