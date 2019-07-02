<?php
    use yii\helpers\Html;
?>
<div class='product-number'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('productNumber')?>:&nbsp;
    </span>
    <?=$model->productNumber?>
</div>
<div class='brand-name'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('brandName')?>:&nbsp;
    </span>
    <?=$model->brandName?>
</div>
<div class='product-name'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('productName')?>:&nbsp;
    </span>
    <?=$model->productName?>
</div>
<div class='price'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('price')?>:&nbsp;
    </span>
    <?=$model->price?>
</div>
<div class='count'>
    <span class='add-info'>
        <?=$model->getAttributeLabel('count')?>:&nbsp;
    </span>
    <?=$model->count?>
</div>

<?php if ($model->order->is_complete==0):?>
    <div class="action-column">
    <?php 
        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
        echo Html::a($icon, ['update',
                                'order_id'=>$model->order_id, 
                                'product_id'=>$model->product_id],['title'=>'Редактировать']);
    ?>

    <?php 
        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
        echo Html::a($icon, ['delete',
                                'order_id'=>$model->order_id, 
                                'product_id'=>$model->product_id],
                            ['title'=>'Удалить',            
                            'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                            'data-method' => 'post',]);
    ?>

    </div>
<?php endif ?>