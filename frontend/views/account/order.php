<h3>Мои заказы:</h3>
<?php
    $header = "<div class='row-header order-data'>
<div class='number'>{$order->getAttributeLabel('id')}</div>
<div class='created-at'>{$order->getAttributeLabel('created_at')}</div>
<div class='status-name'>{$order->getAttributeLabel('statusName')}</div>
<div class='price-sum'>{$order->getAttributeLabel('price_sum')}</div>
<div class='view-products'></div>
</div>";
    echo yii\widgets\ListView::widget([
        'dataProvider' => $order->dataProvider,
        'layout' => "{summary}$header\n{items}\n{pager}",
        'itemView' => '_itemOrder',
        'itemOptions' => ['tag' => 'div','class'=>'order-data']
    ]);
?>