<br>
<div><b><?=$model->getAttributeLabel('number')?>:</b> 
    <span class="number"><?=$model->number?></span>
</div>
<div><b><?=$model->getAttributeLabel('brandName')?>:</b> 
    <span class="brandName"><?=$model->brandName?></span>
</div>
<div><b><?=$model->getAttributeLabel('name')?>:</b> <?=$model->name?></div>
<?php if ($model->count):?>
    <div><b><?=$model->getAttributeLabel('price')?>:</b> <?=$model->price?> руб.</div>
    <div><b><?=$model->getAttributeLabel('custPrice')?>:</b> 
        <span class="custPrice"><?=$model->custPrice?></span> руб.
    </div>
    <div><b><?=$model->getAttributeLabel('count')?>:</b> <?=$model->count?> шт.</div>
    <?=\yii\helpers\Html::a('Добавить', '#',['class'=>'add-product']);?>
<?php else: ?>
    <div><?=$model->countView?></div>
<?php endif; ?>