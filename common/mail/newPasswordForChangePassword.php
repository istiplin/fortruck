<?php
    use yii\helpers\Html;
    use yii\helpers\Url;

    $domain = str_replace(Url::base(), '', Url::base(true));
?>
Ваш новый пароль для сайта <?=Html::a($domain,$domain)?>.
<br>
<br>
<?=$password?>