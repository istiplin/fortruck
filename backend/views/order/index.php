<?php
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = 'Список заказов';
$this->params['breadcrumbs'][] = [
                                    'label' => $searchModel->statusesName, 
                                    'url' => ['users',
                                                'is_complete'=>$searchModel->is_complete
                                ]];
$this->params['breadcrumbs'][] = $searchModel->user->email;
?>
<div class="order-index">
    <?= DetailView::widget([
        'model' => $searchModel->user,
        'attributes' => [
            'name',
            'phone',
        ],
    ]) ?>
    <h2><?=$this->title?></h2>
    
    <?php ob_start(); ?>    
        <div class='order header item'>
            <div class='order-number'><?=$searchModel->getAttributeLabel('id')?></div>
            <?php if (!$searchModel->is_complete):?>
                <div class='created-at'><?=$searchModel->getAttributeLabel('created_at')?></div>
            <?php endif; ?>
            <?php if ($searchModel->is_complete):?>
                <div class='complete-time'><?=$searchModel->getAttributeLabel('complete_time')?></div>
            <?php endif; ?>
            <div class='price-sum'><?=$searchModel->getAttributeLabel('price_sum')?></div>
            <div class='comment'><?=$searchModel->getAttributeLabel('comment')?></div>
            <div class='order-item-link-html'><?=$searchModel->getAttributeLabel('orderItemLinkHtml')?></div>
        </div>
    <?php $header = ob_get_clean(); ?>
    
    <?php
        echo yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{summary}$header\n{items}\n{pager}",
            'itemView' => '_itemOrder',
            'itemOptions' => ['tag' => 'div','class'=>'order item']
        ]);
    ?>
</div>
