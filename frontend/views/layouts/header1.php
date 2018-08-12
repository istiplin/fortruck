<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Pjax;


$this->beginContent('@frontend/views/layouts/main.php');
    NavBar::begin([
        //'brandLabel' => Yii::$app->name,
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            //'class' => 'navbar-inverse navbar-fixed-top',
            'class' => 'navbar-inverse',
        ],
    ]);



        $menuItems = [];
        $menuItems[] = ['label' => '', 'url' => Url::base(), 'options'=>['class'=>'logo']];
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $menuItems,
        ]);
        
        $menuItems = [];
        $menuItems[] = '<li>'
            . Html::beginForm(['search'], 'get').'<div class=search-form-container>'
            . Html::input('text', 'text', $this->params['text'], 
                        [
                            'placeholder' => 'Поиск',
                            'size'=>30,
                            'class'=>'input-search',
                        ]
                )
            . Html::submitButton(
                'Найти',
                ['class' => 'btn btn-primary btn-search',]
            )
            . '<div>'.Html::endForm()
            . '</li>';


        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left search'],
            'items' => $menuItems,
        ]);

        echo $this->render('cart_button');

        
    NavBar::end();
?>
<div class="content">
    <div class="container">
        <?=$content;?>
    </div>    
</div>
<?php $this->endContent(); ?>
