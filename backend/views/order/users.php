<?php

$this->title = 'Список покупателей';
$this->params['breadcrumbs'][] = $searchModel->statusesName;

?>
<div class="order-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <h2><?=$this->title?></h2>
    
    <?php ob_start(); ?>    
        <div class='order-user header item'>
            <?php if (!$searchModel->is_complete):?>
                <div class='created-at'><?=$searchModel->getAttributeLabel('created_at')?></div>
            <?php endif; ?>
            <?php if ($searchModel->is_complete):?>
                <div class='complete-time'><?=$searchModel->getAttributeLabel('complete_time')?></div>
            <?php endif; ?>
            <div class='email'><?=$searchModel->getAttributeLabel('email')?></div>
            <div class='user-name'><?=$searchModel->getAttributeLabel('userName')?></div>
            <div class='user-phone'><?=$searchModel->getAttributeLabel('userPhone')?></div>
            <div class='order-count'><?=$searchModel->getAttributeLabel('count')?></div>
            <div class='order-view'><?=$searchModel->getAttributeLabel('orderLinkHtml')?></div>
        </div>
    <?php $header = ob_get_clean(); ?>
    
    <?php
        echo yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{summary}$header\n{items}\n{pager}",
            'itemView' => '_itemUser',
            'itemOptions' => ['tag' => 'div','class'=>'order-user item']
        ]);
    ?>
</div>
