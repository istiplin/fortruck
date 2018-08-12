<?php        
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Nav;

Pjax::begin([
            'formSelector'=>'#right-wrap', 
            'linkSelector'=>false, 
            'enablePushState' => false,
            'id'=>'pjax-right-wrap',
    ]);

    $menuItems = [];
    $menuItems[] = ['label' => 'Корзина', 'url' => ['site/cart']];
    if (Yii::$app->user->isGuest==false)
    {
        //$menuItems[] = '<li>+7(926)-277-77-61</li>';
        if(Yii::$app->user->identity->isAdmin())
            $menuItems[] = ['label' => 'Админка', 'url' => '/admin'];
        $menuItems[] = ['label' => 'Выход ('.Yii::$app->user->identity->email.')', 'url' => ['site/logout']];
    }
    else
    {
        $menuItems[] = ['label' => 'Вход', 'url' => ['site/login']];
        $menuItems[] = ['label' => 'Регистрация', 'url' => ['registration/index']];
    }

    echo Html::beginForm(['site/update-cart-button'], 'post', ['id'=>'right-wrap']);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
    echo Html::endForm();
Pjax::end();
?>