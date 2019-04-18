<?php $this->registerCssFile('@web/css/list_view_style.css'); ?>
<div class='number'><?=$model->number?></div>
<div class='brand'><?=$model->brandName?></div>
<div class='name'><?=$model->name?></div>
<?php if ($model->count):?>
    <div class='price'><?=$model->custPriceView?>
        <?php if(!\Yii::$app->user->isGuest): ?>
            <span class='add-info'> руб.</span>
        <?php endif; ?>
    </div>
    <div class='count'><span class='add-info'>На складе:&nbsp;</span><?=$model->countView?> шт.</div>
    <?php if(!\Yii::$app->user->isGuest):?>
        <div class='cart-qty'><span class='add-info'>В корзине:&nbsp;</span><?=$model->cartCountView?>&nbsp;шт.</div>
        <?=$model->cartView?>
    <?php endif; ?>
<?php else: ?>
    <div class='count'><?=$model->countView?></div>
<?php endif; ?>