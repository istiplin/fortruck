<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\models\Config;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use common\widgets\registration\RegistrationWidget;
use common\widgets\restorePassword\RestorePasswordWidget;
use common\widgets\auth\AuthWidget;
use common\widgets\requestPrice\RequestPriceWidget;

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
    
    <?php if(Yii::$app->user->isGuest): ?>
        <?= RequestPriceWidget::widget([
                                        'id'=>'request-price',
                                        'activeFormConfig'=>[
                                                                //'id' => 'request-price-form',
                                                                'action'=>['site/request-price'],
                                                                'enableClientValidation' => true,
                                                ]]); ?>
    
        <?= AuthWidget::widget([
                                'id'=>'auth',
                                'activeFormConfig'=>[
                                                        //'id' => 'login-form',
                                                        'action'=>['site/auth'],
                                                        'enableClientValidation' => false,
                                                        'enableAjaxValidation'=>true,
                                                        'options'=>['class'=>'form-signin','name'=>'authcheck'],
                                            ]]); ?>
    
    <?php endif; ?>

    <?= RestorePasswordWidget::widget([
                                        'id'=>'restore-password',
                                        'activeFormConfig'=>[
                                                                //'id' => 'restore-password-form',
                                                                'action'=>['site/restore-password'],
                                                                'enableClientValidation' => true,
                                                    ]]); ?>
    
    <?= RegistrationWidget::widget([
                                    'id'=>'registration',
                                    'activeFormConfig'=>[
                                                        //'id' => 'registration-form',
                                                        'action'=>['site/registration'],
                                                        'enableClientValidation' => true,
                                        ]]); ?>
    
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
