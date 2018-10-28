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
                                    'label' => $searchModel->order->statusName, 
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
            [
                'label'=>'Имя покупателя',
                'value'=>function($data){
                    return $data->user->name;
                },
                //'visible'=>$searchModel->order->is_complete==0
            ],
            [
                'label'=>'Телефон',
                'value'=>function($data){
                    return $data->user->phone;
                },
                //'visible'=>$searchModel->order->is_complete==0
            ],
            'comment',
            [
                'label'=>'Статус заказа',
                'value'=>function($data)
                {
                    return ($data['is_complete']?'Завершен':'Не завершен');
                }
            ],
                    /*
            [
                'label'=>'Дата создания',
                'value'=>function($data)
                {
                    return $data['created_at'];
                },
                'visible'=>!$searchModel->order->is_complete,
            ],
                     * 
                     */
            [
                'label'=>'Дата завершения',
                'value'=>function($data)
                {
                    return $data['complete_time'];
                },
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
        //'filterModel' => $searchModel,
        'columns' => [
            [
                'label'=>'Артикул',
                'value'=>function($data)
                {
                    return $data->product->number;
                }
            ],
            [
                'label'=>'Наименование продукта',
                'value'=>function($data)
                {
                    return $data->product->name;
                }
            ],
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
