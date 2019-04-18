<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
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
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php if($searchModel->order->is_complete==0): ?>
        <p>
            <?= Html::a('Добавить товар', ['create','order_id'=>$searchModel->order_id], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'productNumber',
            'brandName',
            'productName',
            'price',
            'count',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}',
                'visible' => $searchModel->order->is_complete==0
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
