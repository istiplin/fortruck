<?php
namespace common\components;

use Yii;
use common\models\User;
use common\models\Config;

class Application extends \yii\web\Application
{
    public function init()
    {
        parent::init();
        Yii::$app->mailer->transport->setUsername(Config::value('site_email'));
        Yii::$app->mailer->transport->setPassword(Config::value('site_email_password'));
        //Yii::$app->mailer->messageConfig['from']=array('sdfsf'=>'df');
        //echo Yii::$app->mailer->transport->getUserName();die();
    }
}
?>
