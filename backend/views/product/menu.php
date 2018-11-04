<?php

use yii\helpers\Html;

?>

<h3><?=Html::a('Список товаров', ['index'])?></h3>
<h3><?=Html::a('Цены на товар', ['price-product/index', 'sort'=>'price_change_time'])?></h3>