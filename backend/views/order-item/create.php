<?php

use yii\helpers\Html;

use yii\helpers\Url;
use kartik\select2\Select2;
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

    <?php
        // echo $form->field($model, 'product_id')->widget(Select2::classname(), [
        //    'value' => $model->product_id,
        //    'initValueText' => $model->productNumber.' ('.$model->product->brandName.')',
        echo Select2::widget([
            'name' => 'product_id',
            'value' => $model->product_id,
            'initValueText' => $model->productNumber.' ('.$model->product->brandName.')',
            'options' => ['placeholder' => 'Введите артикул товара'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => Url::to(['product/number-list']),
                    'dataType' => 'json',
                    //'success' => new \yii\web\JsExpression('function(){alert(9)}')
                ],
            ]
        ]);
    ?>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
