<?php

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\models\Config;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use common\widgets\cart\CartWidget;
use yii\bootstrap\Modal;
use yii\web\View;

AppAsset::register($this);

$baseUrl = Yii::$app->request->baseUrl;
$js = <<<JS
BASE_URL = '$baseUrl';
JS;
$this->registerJS($js,View::POS_HEAD);

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
            © <?=date('Y')?>, Все права защищены.
        </div>
        <div class='foot-center'>
            <div class='footerElem'>
                <div>Интернет-магазин грузовых автозапчастей.</div>
                <div>Доставка по всей России</div>
            </div>
            <div class='footerElem'>
                <div><span>Наш телефон: </span><nobr><?= Config::value('site_phone') ?></nobr></div>
                <div><span>Наша почта: </span><?= Html::mailto(Config::value('site_email')) ?></div>
            </div>
        </div>

    </div>
    
    <?=Alert::widget([
        'closeButton'=>[
            'data-dismiss'=>false,
        ],
        'body'=>'<span class="alert-message"></span>'
    ])?>
    
    <?php if(Yii::$app->session->hasFlash('is_checkout')): ?>

        <?php Modal::begin([
            'id'=>'checkout-confirm-message',
            'closeButton'=>false,
            'clientOptions' => ['show' => true],
        ]);
        ?>

        <div class="popup-text">
            <h3>
                <?php if(Yii::$app->session->getFlash('is_checkout')): ?>
                    <div>Заявка отправлена.</div>
                    <div>Наш менеджер свяжется с Вами в ближайшее время.</div>
                <?php endif; ?>
            </h3>
            <?=Html::button('ОК',['class'=>'btn btn-default', 'data-dismiss'=>'modal'])?>
        </div>

        <?php Modal::end(); ?>
    <?php endif ?>
    
    <?php 
        Modal::begin([
            'header'=>'<h4>Вы уверены, что хотите оформить заказ?</h4>',
            'id'=>'checkout-modal',
            'size'=>Modal::SIZE_LARGE,
        ]);
    ?>
        <?=Html::a('ДА',['site/cart','form_order'=>1],['class'=>'btn btn-success'])?>
        <?=Html::button('НЕТ',['class'=>'btn btn-danger','data-dismiss'=>'modal'])?>
    <?php Modal::end(); ?>
    
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
