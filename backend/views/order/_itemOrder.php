<div class='order-number'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('id')?>:&nbsp;
    </span>
    <?=$model->id?>
</div>

<?php if(!$model->is_complete):?>
    <div class='created-at'>
        <span class='add-info'>
            <?=$model->getAttributeLabel('created_at')?>:&nbsp;
        </span>
        <?=$model->created_at?>
    </div>
<?php endif; ?>

<?php if($model->is_complete):?>
    <div class='complete-time'>
        <span class='add-info'>
            <?=$model->getAttributeLabel('complete_time')?>:&nbsp;
        </span>
        <?=$model->complete_time?>
    </div>
<?php endif; ?>

<div class='price-sum'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('price_sum')?>:&nbsp;
    </span>
    <?=($model->price_sum)?$model->price_sum:'<span class="not-set">(не задано)</span>'?>
</div>

<div class='comment'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('comment')?>:&nbsp;
    </span>
    <?=($model->comment)?($model->comment):'-'?>
</div>

<div class='order-item-link-html'>
    <?=$model->orderItemLinkHtml?>
</div>