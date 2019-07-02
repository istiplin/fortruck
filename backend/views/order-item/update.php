<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */

$this->params['breadcrumbs'][] = [
                                    'label' => $model->order->statusName, 
                                    'url' => ['order/users',
                                                'is_complete'=>$model->order->is_complete
                                            ]
                                ];
$this->params['breadcrumbs'][] = [
                                    'label' => $model->order->user->email, 
                                    'url' => ['order/index',
                                                'user_id'=>$model->order->user->id,
                                                'is_complete'=>$model->order->is_complete
                                            ]
                                ];
$this->params['breadcrumbs'][] = ['label' => 'Заказ №'.$model->order->id, 'url' => ['order-item/index', 'order_id'=>$model->order->id]];
$this->params['breadcrumbs'][] = $model->product->number.' ('.$model->product->brandName.')';
?>
<div class="order-item-update">

    <h2>Информация о товаре:</h2>

    <?= DetailView::widget([
            'model' => $custProduct,
            'attributes' => [
                'number',
                'brandName',
                'name',
                'price',
                'custPrice',
            ],
    ])
    ?>
    <?php $this->title = 'Информация о заказе'; ?>
    <h2><?=$this->title?>:</h2>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
