<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    
    $domain = str_replace(Url::base(), '', Url::base(true));
    $link = Yii::$app->urlManager->createAbsoluteUrl(['registration/confirm-mail', 'id'=>$user->id, 'operation_key'=>$user->operation_key]);
?>
Здравствуйте <?=$user->name?>!
<br>
<br>
Вы регистрируетесь на сайте <?= Html::a($domain,$domain)?>.
<br>
Для подтверждения регистрации перейдите по <?= Html::a('ccылке',$link)?>.
<br>
<br>
Если это были не Вы, проигнорируйте данное сообщение.