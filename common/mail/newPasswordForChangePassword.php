<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use common\models\Config;

    $domain = str_replace(Url::base(), '', Url::base(true));
	$domain_name=Config::value('domain_name');
?>
Ваш новый пароль для сайта <?=Html::a($domain_name,$domain)?>.
<br>
<br>
<?=$password?>