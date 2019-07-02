<?php

use yii\helpers\Html;
use common\widgets\productSearcher\ProductSearcherWidget;
use yii\web\View;

$this->title = 'Добавление товара';
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

    <h1><?=Html::encode($this->title) ?></h1>
    <?php $id = 'product-searcher'; ?>
    <?=Html::a('Добавить новый товар','#',['data-toggle'=>'modal','data-target'=>'#product-searcher-modal']);?>
    <?=ProductSearcherWidget::widget([
        'id'=>$id,
        'route'=>'order-item/product-searcher',
    ])?>

    <?=$this->render('_form', [
        'model' => $model,
    ]) ?>

    <?=$this->registerJS(require 'script.js',View::POS_END); ?>
</div>