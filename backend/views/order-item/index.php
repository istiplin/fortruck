<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список товаров';
$this->params['breadcrumbs'][] = [
                                    'label' => $searchModel->order->statusesName, 
                                    'url' => ['order/users',
                                                'is_complete'=>$searchModel->order->is_complete
                                            ]
                                ];
$this->params['breadcrumbs'][] = [
                                    'label' => $searchModel->order->user->email, 
                                    'url' => ['order/index',
                                                'user_id'=>$searchModel->order->user->id,
                                                'is_complete'=>$searchModel->order->is_complete
                                            ]
                                ];
$this->params['breadcrumbs'][] = 'Заказ №'.$searchModel->order->id;
?>
<div class="order-item-index">

    <!-- <h2>Информация о заказе</h2> -->
    <?= DetailView::widget([
        'model' => $searchModel->order,
        'attributes' => [
            'id',
            'userName',
            'userPhone',
            'comment',
            'statusName',
            [
                'attribute'=>'complete_time',
                'visible'=>$searchModel->order->is_complete,
            ],
        ],
    ]) ?>
    
    <?= Html::a('Редактировать', ['order/update', 'id' => $searchModel->order->id], ['class' => 'btn btn-primary']) ?>
    
    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php if($searchModel->order->is_complete==0): ?>
        <p>
            <?= Html::a('Добавить товар', ['create','order_id'=>$searchModel->order_id], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php ob_start(); ?>    
        <div class='order-item header item'>
            <div class='product-number'><?=$searchModel->getAttributeLabel('productNumber')?></div>
            <div class='brand-name'><?=$searchModel->getAttributeLabel('brandName')?></div>
            <div class='product-name'><?=$searchModel->getAttributeLabel('productName')?></div>
            <div class='price'><?=$searchModel->getAttributeLabel('price')?></div>
            <div class='count'><?=$searchModel->getAttributeLabel('count')?></div>
        </div>
    <?php $header = ob_get_clean(); ?>
    <?php
        echo yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{summary}$header\n{items}\n{pager}",
            'itemView' => '_item',
            'itemOptions' => ['tag' => 'div','class'=>'order-item item']
        ]);
    ?>
        
</div>
