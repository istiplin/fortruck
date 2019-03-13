<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use yii\helpers\Url;
use common\models\User;
use common\models\Order;

AppAsset::register($this);
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
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php if (!Yii::$app->user->isGuest): ?>
    <?php
        NavBar::begin([
            //'brandLabel' => Yii::$app->name,
            //'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
    
        if(Yii::$app->user->identity->isAdmin())
        {
            $menuItems = [];
            $menuItems[] = ['label' => 'Товары', 'url' => ['/product/index']];
            
            $label = 'Пользователи';
            $count = User::getMailConfirmedCount();
            if ($count>0)
                $label.='('.$count.')';
            $menuItems[] = ['label' => $label, 'url' => ['/user/index']];
            
            $label = 'Заказы';
            $count = Order::getIsNotComplete();
            if ($count>0)
                $label.='('.$count.')';
            $menuItems[] = ['label' => $label, 'url' => ['/order/users']];
            
            $menuItems[] = ['label' => 'Настройки', 'url' => ['/config/index']];
            
            $menuItems[] = ['label' => 'Интеграция', 'url' => ['/integration/index']];

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => $menuItems,
            ]);
        }
    
        $menuItems = [];
        
        $shopUrl = str_replace('admin','shop',Yii::$app->request->baseUrl);
        $menuItems[] = ['label' => 'Online-Магазин', 'url' => Url::to($shopUrl)];
        
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выход',
                [
                    'class' => 'btn btn-link logout',
                    'title' => Yii::$app->user->identity->email
                ]
            )
            . Html::endForm()
            . '</li>';
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);

        NavBar::end();
    ?>
    <?php endif; ?>
    
    <div class="container">
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Breadcrumbs::widget([
                'homeLink'=>false,
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
