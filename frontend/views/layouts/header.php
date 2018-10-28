<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Pjax;

use frontend\models\cart\Cart;

?>
<?php $this->beginContent('@frontend/views/layouts/main.php'); ?>

<div class="head">
    <div class="topHeadBlock">
        <div class="logo">
            <?=Html::a('', Url::base())?>
        </div>
        <div class="headMiddle">
            <div class="wSearchForm">
                <?=Html::beginForm(['site/search'], 'get');?>
                    <div class="searchFormContainer">
                        <div class="code">
                            
                            <?=Html::input('text', 'text', $this->params['text'],[
                                                        'placeholder' => 'Введите код запчасти',
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
                        <?=Html::a('Вход',['site/login'])?> | <?=Html::a('Регистрация',['registration/index'])?>
                    <?php else: ?>
                        <?=Html::a('Выход ('.Yii::$app->user->identity->email.')',['site/logout'])?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="wCart">
                <div class="cart">
                    <?=Html::a(Html::img('@web/img/cart.png'),['site/cart'])?>
                </div>
                <div class="basketLegend">
                    товаров: <span class="qty"><?=Cart::initial()->getCountSum();?></span>, 
                    <span class="moneySumm"><?=Cart::initial()->getPriceSum();?></span>
                </div>
                <?php //<a href="http://spare-wheel.ru/cart/" class="makeOrder">Оформить</a> ?>
                <?=Html::a('Оформить',['site/cart'], ['class'=>'makeOrder'])?>
            </div>
        </div>
    </div>
<?php
    NavBar::begin([
        //'brandLabel' => Yii::$app->name,
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            //'class' => 'navbar-inverse navbar-fixed-top',
            'class' => 'navbar-inverse',
        ],
    ]);
    
        $menuItems = [];
        //$menuItems[] = ['label' => 'Доставка', 'url' => ''];
        $menuItems[] = ['label' => 'Личные данные', 'url' => ['account/index']];
        $menuItems[] = ['label' => 'Мои заказы', 'url' => ['account/order']];
        $menuItems[] = ['label' => 'Корзина', 'url' => ['site/cart']];
        $menuItems[] = ['label' => 'Выход', 'url' => ['logout'], 'options'=>['class'=>'logout']];
        //$menuItems[] = ['label' => 'О нас', 'url' => ''];
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $menuItems,
        ]);
        
        if(!Yii::$app->user->isGuest and Yii::$app->user->identity->isAdmin())
        {
            $menuItems = [];
            $menuItems[] = ['label' => 'Админка', 'url' => '/admin'];
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
