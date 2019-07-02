<?=$search->title?>
<?php 
    $header = "<div class='row-header product-data'>
<div class='number'>{$search->getAttributeLabel('number')}</div>
<div class='brand'>{$search->getAttributeLabel('brandName')}</div>
<div class='name'>{$search->getAttributeLabel('name')}</div>
<div class='price'>{$search->getAttributeLabel('custPriceView')}</div>
<div class='count'>{$search->getAttributeLabel('count')}</div>
</div>";
    echo yii\widgets\ListView::widget([
        'dataProvider' => $search->dataProvider,
        'layout' => "{summary}$header\n{items}\n{pager}",
        'itemView' => '_itemOrderItem',
        'itemOptions' => ['tag' => 'div','class'=>'product-data']
    ]);
?>
<h4><b>Итого:</b> <?=$search->priceSum?></h4>