<div class='row-header'>
    <div class='number'><?=$search->getAttributeLabel('number')?></div>
    <div class='brand'><?=$search->getAttributeLabel('brandName')?></div>
    <div class='name'><?=$search->getAttributeLabel('name')?></div>
    <div class='price'><?=$search->getAttributeLabel('custPriceView')?></div>
    <div class='count'><?=$search->getAttributeLabel('countView')?></div>
    <?php if(!\Yii::$app->user->isGuest):?>
        <div class='cart-qty'><?=$search->getAttributeLabel('cartCountView')?></div>
        <div class='add-to-cart'><?=$search->getAttributeLabel('cartView')?></div>
    <?php endif; ?>
</div>