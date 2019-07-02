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

<div class='email'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('email')?>:&nbsp;
    </span>
    <?=$model->email?>
</div>

<div class='user-name'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('userName')?>:&nbsp;
    </span>
    <?=$model->userName?>
</div>

<div class='user-phone'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('userPhone')?>:&nbsp;
    </span>
    <?=$model->userPhone?>
</div>

<div class='order-count'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('count')?>:&nbsp;
    </span>
    <?=$model->count?>
</div>

<div class='order-view'><?=$model->orderLinkHtml?></div>