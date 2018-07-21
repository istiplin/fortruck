<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

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
        
            
        
            $menuItems = [];
            $menuItems[] = ['label' => '', 'url' => '', 'options'=>['class'=>'logo']];
            $menuItems[] = '<li>'
                . Html::beginForm([''], 'post')
                . Html::input('text', 'search', Yii::$app->request->post()['search'], 
                            [
                                'placeholder' => 'Поиск по артиклу:',
                                'size'=>30
                            ]
                    )
                . Html::submitButton(
                    'Поиск',
                    ['class' => 'btn btn-link search',]
                )
                . Html::endForm()
                . '</li>';
                
                
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => $menuItems,
            ]);
        
            
            
            
            $menuItems = [];
            //$menuItems[] = '<li>+7(926)-277-77-61</li>';
            if(Yii::$app->user->identity->isAdmin())
                $menuItems[] = ['label' => 'Админка', 'url' => '/admin'];
            $menuItems[] = ['label' => 'Выход ('.Yii::$app->user->identity->email.')', 'url' => ['site/logout']];

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
        
        NavBar::end();
    ?>
    <?php endif; ?>
    
    <div class="container">
	<?=$content;?>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
