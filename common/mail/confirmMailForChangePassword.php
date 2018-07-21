<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    
    $domain = str_replace(Url::base(), '', Url::base(true));
    $link = Yii::$app->urlManager->createAbsoluteUrl(['restore-password/change-password', 'id'=>$user->id, 'operation_key'=>$user->operation_key]);
?>
Здравствуйте <?=$user->name?>!
<br>
<br>
Вы собираетесь сменить пароль на сайте <?= Html::a($domain,$domain)?>.
<br>
Для смены пароля перейдите по <?= Html::a('ccылке',$link)?>.
<br>
<br>
Если это были не Вы, проигнорируйте данное сообщение.