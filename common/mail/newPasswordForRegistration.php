<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    
    $domain = str_replace(Url::base(), '', Url::base(true));
?>
Здравствуйте <?=$user->name?>!
<br>
Ваша заявка на сайте <?=Html::a($domain,$domain)?> рассмотрена.
<br>
<br>
Ниже представлены данные для входа в систему <?=Html::a($domain,$domain)?>:
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;Логин: <?=$user->email?>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;Пароль: <?=$password?>
<br>
<br>
Никому не сообщайте свои данные!