<?php $this->registerCssFile('@web/css/list_view_style.css'); ?>
<div class='number'><?=$model->number?></div>
<div class='brand'><?=$model->brandName?></div>
<div class='name'><?=$model->name?></div>
<?php if ($model->count):?>
    <div class='price'><?=$model->custPriceView?><span class='add-info'>&nbsp;руб.</span></div>
    <div class='cart-qty'><span class='add-info'>В корзине:&nbsp;</span><?=$model->cartCountView?>&nbsp;шт.</div>
    <?=$model->cartView?>
<?php else: ?>
    <div class='count'><?=$model->countView?></div>
<?php endif; ?>