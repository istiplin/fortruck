<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\models\Config;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use frontend\widgets\registrationForm\RegistrationFormWidget;
use frontend\widgets\restorePasswordForm\RestorePasswordFormWidget;
use frontend\widgets\loginForm\LoginFormWidget;

AppAsset::register($this);
$this->title = "Грузовые автозапчасти For Trucks";
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" href="<?=Url::to("@web/img/title.ico")?>">
    
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrap">
        <?=$content;?>
        <div class="pusher"></div>
    </div>
    <div class="foot">
        <div class='foot-copyright'>
            © 2018, Все права защищены.
        </div>
        <div class='foot-center'>
            <div class='footerElem'>
                Интернет-магазин грузовых автозапчастей.
                <br>
                Доставка по всей России
            </div>
            <div class='footerElem footerElem-small'>
                Контакты: 
                <br>
                <nobr><?= Config::value('site_phone') ?></nobr>
                <br>
                <?= Html::mailto(Config::value('site_email')) ?>
            </div>
            <div class='footerElem footerElem-big'>
                Наш телефон: <nobr><?= Config::value('site_phone') ?></nobr>
                <br>
                Наша почта: <?= Html::mailto(Config::value('site_email')) ?>
            </div>
        </div>

    </div>
    
    <?=Alert::widget([
        'closeButton'=>[
            'data-dismiss'=>false,
        ],
        'body'=>'<span class="alert-message"></span>'
    ])?>
    
    <?= LoginFormWidget::widget(['activeFormConfig'=>[
                                                        'id' => 'login-form',
                                                        'action'=>['site/login'],
                                                        'enableClientValidation' => false,
                                                        'options'=>['class'=>'form-signin','name'=>'authcheck'],
                                                        'validationUrl' => ['site/login-validate'],
                                                        'enableAjaxValidation'=>true,
                                        ]]); ?>
    
    <?= RegistrationFormWidget::widget(['activeFormConfig'=>[
                                                        'id' => 'registration-form',
                                                        'action'=>['site/registration-save'],
                                                        'enableClientValidation' => true,
                                                        //'validationUrl' => ['site/registration-validate'],
                                                        //'enableAjaxValidation'=>true,
                                        ]]); ?>
    
    <?= RestorePasswordFormWidget::widget(['activeFormConfig'=>[
                                                        'id' => 'restore-password-form',
                                                        'action'=>['site/restore-password-send-confirm-message'],
                                                        'enableClientValidation' => true,
                                                        //'validationUrl' => ['site/registration-validate'],
                                                        //'enableAjaxValidation'=>true,
                                        ]]); ?>
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
