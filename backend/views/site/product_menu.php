<?php
    use yii\helpers\Html;
    
    $this->params['breadcrumbs'][] = 'Меню для работы с товарами';
?>

<h2><?=Html::a('Список производителей',['producer/index'])?></h2>
<h2><?=Html::a('Список типов аналогов',['analog/index'])?></h2>
<h2><?=Html::a('Список товаров',['product/index'])?></h2>
