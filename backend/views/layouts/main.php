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
            //$menuItems[] = ['label' => 'Аналоги', 'url' => ['/analog']];
            //$menuItems[] = ['label' => 'Производители', 'url' => ['/producer']];
            //$menuItems[] = ['label' => 'Товары', 'url' => ['/product']];
            $menuItems[] = ['label' => 'Товары', 'url' => ['site/product-editor-menu']];
            
            $menuItems[] = ['label' => 'Пользователи', 'url' => ['/user']];
            $menuItems[] = ['label' => 'Заказы', 'url' => ['/order']];
            $menuItems[] = ['label' => 'Настройки', 'url' => ['/config']];
            

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => $menuItems,
            ]);
        }
    
        $menuItems = [];
        $menuItems[] = ['label' => 'Online-Магазин', 'url' => Url::to('/shop')];
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
