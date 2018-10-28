<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */

$this->title = 'Изменение данных о товаре';
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
$this->params['breadcrumbs'][] = $model->product->number;
?>
<div class="order-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label'=>'Артикул',
                    'value'=>function($data){
                        return $data->product->number;
                    }
                ],
                [
                    'label'=>'Наименование',
                    'value'=>function($data){
                        return $data->product->name;
                    }
                ],
                'price'
            ],
    ])
    ?>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
