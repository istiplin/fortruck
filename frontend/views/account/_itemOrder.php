<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<?php $this->registerCssFile('@web/css/list_view_style.css'); ?>
<div class='number'><span class='add-info'><?=$model->getAttributeLabel('id')?>:&nbsp;</span><?=$model['id']?></div>
<div class='created-at'><span class='add-info'><?=$model->getAttributeLabel('created_at')?>:&nbsp;</span><?=$model['created_at']?></div>
<div class='status-name'><span class='add-info'><?=$model->getAttributeLabel('statusName')?>:&nbsp;</span><?=$model['statusName']?></div>
<div class='price-sum'><span class='add-info'><?=$model->getAttributeLabel('price_sum')?>:&nbsp;</span><?=$model['price_sum']?$model['price_sum']:0?></div>
<?php $url = Url::to(['order-item','id'=>$model['id']]);?>
<div class='view-products'><?=Html::a('Посмотреть товары', $url)?></div>