<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;

$this->beginContent('@frontend/views/layouts/main.php');
    NavBar::begin([
        //'brandLabel' => Yii::$app->name,
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);



        $menuItems = [];
        $menuItems[] = ['label' => '', 'url' => Url::base(), 'options'=>['class'=>'logo']];
        $menuItems[] = '<li>'
            . Html::beginForm(['search'], 'get')
            . Html::input('text', 'text', $this->params['text'], 
                        [
                            'placeholder' => 'Поиск',
                            'size'=>30
                        ]
                )
            . Html::submitButton(
                'Найти',
                ['class' => 'btn btn-link search',]
            )
            . Html::endForm()
            . '</li>';


        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $menuItems,
        ]);


        $menuItems = [];
        $menuItems[] = ['label' => 'Корзина', 'url' => ['site/bag']];
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

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
    NavBar::end();
?>
    <div class="container">
        <?=$content;?>
    </div>    
<?php $this->endContent(); ?>
