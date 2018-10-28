<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
	use common\models\Config;
    
    $domain = str_replace(Url::base(), '', Url::base(true));
	$domain_name=Config::value('domain_name');
    $link = Yii::$app->urlManager->createAbsoluteUrl([$mailConfirmUrl, 'id'=>$user->id, 'operation_key'=>$user->operation_key]);
?>
Здравствуйте <?=$user->name?>!
<br>
<br>
Вы регистрируетесь на сайте <?= Html::a($domain_name,$domain)?>.
<br>
Для подтверждения регистрации перейдите по <?= Html::a('ccылке',$link)?>.
<br>
<br>
Если это были не Вы, проигнорируйте данное сообщение.