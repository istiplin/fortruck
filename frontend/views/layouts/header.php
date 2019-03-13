<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\Config;

use frontend\models\cart\Cart;

use common\widgets\auth\AuthWidget;
?>
<?php $this->beginContent('@frontend/views/layouts/main.php'); ?>

<div class="head">
    <div class="topHeadBlock">
        <div class="logo-home-page">
            <div class="logo">
                <?=Html::a('', Url::base())?>
            </div>
            <div class="home-page">
                <?php 
                    $homeUrl = str_replace('shop','',Yii::$app->request->baseUrl);
                ?>
                <?=Html::a(Config::value('domain_name'), $homeUrl)?>
            </div>
        </div>
        <div class="headMiddle">
            <div class="wSearchForm">
                <?=Html::beginForm(['site/search'], 'get');?>
                    <div class="searchFormContainer">
                        <div class="code">
                            
                            <?=Html::input('text', 'number', $this->params['number'],[
                                                        'placeholder' => 'Введите код или наименование запчасти',
                                                        ]);
                             ?>
                             
                        </div>
                        <div class="find">
                            <?=Html::submitButton('',['class' => 'btn btn-primary btn-search']) ?>
                        </div>
                    </div>
                <?=Html::endForm();?>
                
            </div>
        </div>
        <div class="headRight">
            <div class="wVisualFormLogin">
                <div class="loginLinks">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <?=Html::a('Вход','',['data-toggle'=>'modal','data-target'=>'#auth-modal'])?> | 
                        <?=Html::a('Регистрация',['registration/index'], ['data-toggle'=>'modal','data-target'=>'#registration-modal'])?>
                    <?php else: ?>
                        <?=Html::a('Выход ('.Yii::$app->user->identity->email.')',AuthWidget::getLogoutUrl('site/auth'))?>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="wCart">
                    <div class="cart">
                        <?=Html::a(Html::img('@web/img/cart.png'),['site/cart'])?>
                    </div>
                    <div class="basketLegend">
                        товаров: <span class="qty"><?=Cart::initial()->getCountSum();?></span>, 
                        <span class="moneySumm"><?=Cart::initial()->getPriceSum();?></span>
                    </div>
                    <?=Html::a('Оформить',['site/cart'], ['class'=>'makeOrder'])?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php

    NavBar::begin([
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);

        $menuItems = [];
        $menuItems[] = ['label' => 'Личные данные', 'url' => ['account/index']];
        $menuItems[] = ['label' => 'Мои заказы', 'url' => ['account/order']];
        $menuItems[] = ['label' => 'Корзина', 'url' => ['site/cart']];
        if (!Yii::$app->user->isGuest)
            $menuItems[] = ['label' => 'Выход', 'url' => AuthWidget::getLogoutUrl('site/auth'), 'options'=>['class'=>'logout']];
        else
            $menuItems[] = ['label' => 'Вход', 'options'=>['class'=>'login','data-toggle'=>'modal','data-target'=>'#auth-modal']];
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $menuItems,
        ]);

        if(!Yii::$app->user->isGuest and Yii::$app->user->identity->isAdmin())
        {
            $menuItems = [];
            
            $adminUrl = str_replace('shop','admin',Yii::$app->request->baseUrl);
            $menuItems[] = ['label' => 'Админка', 'url' => Url::to($adminUrl)];
            //$menuItems[] = ['label' => 'Админка', 'url' => '/admin'];
            
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
        }

    NavBar::end();

?>
            
</div>

<div class="content">
    <div class="container">
        <?=$content;?>
    </div>    
</div>
<?php $this->endContent(); ?>
