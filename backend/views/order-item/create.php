<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */

$this->title = 'Добавить товар';
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
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
