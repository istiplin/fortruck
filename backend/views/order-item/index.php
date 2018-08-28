<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список товаров';
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['order/users']];
$this->params['breadcrumbs'][] = [
                                    'label' => $searchModel->order->user->email, 
                                    'url' => ['order/index','user_id'=>$searchModel->order->user->id]
                                ];
$this->params['breadcrumbs'][] = 'Заказ №'.$searchModel->order->id;
?>
<div class="order-item-index">

    <h2>Информация о заказе</h2>
    <?= DetailView::widget([
        'model' => $searchModel->order,
        'attributes' => [
            [
                'label'=>'Имя покупателя',
                'value'=>function($data){
                    return $data->user->name;
                }
            ],
            [
                'label'=>'Телефон',
                'value'=>function($data){
                    return $data->user->phone;
                }
            ],
            'is_complete:text:Статус заказа',

        ],
    ]) ?>
    
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
