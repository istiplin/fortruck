<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
	use common\models\Config;
    
    $domain = str_replace(Url::base(), '', Url::base(true));
    $domain_name=Config::value('domain_name');
?>
Здравствуйте <?=$user->name?>!
<br>
Ваша заявка на сайте <?=Html::a($domain_name,$domain)?> рассмотрена.
<br>
<br>
Ниже представлены данные для входа в систему <?=Html::a($domain_name,$domain)?>:
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;Логин: <?=$user->email?>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;Пароль: <?=$password?>
<br>
<br>
Никому не сообщайте свои данные!