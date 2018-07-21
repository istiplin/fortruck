<?php
namespace app\components;

use Yii;
use common\models\User;

class Application extends \yii\web\Application
{
    public function init()
    {
        parent::init();
        //Yii::$app->mailer->transport->setUsername('f11gfgfg');
        //Yii::$app->mailer->transport->setPassword('454');
        //Yii::$app->mailer->messageConfig['from']=array('sdfsf'=>'df');
        //echo Yii::$app->mailer->transport->getUserName();die();
    }
}
?>
