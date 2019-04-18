<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список заказов';
$this->params['breadcrumbs'][] = [
                                    'label' => $searchModel->statusesName, 
                                    'url' => ['users',
                                                'is_complete'=>$searchModel->is_complete
                                ]];
$this->params['breadcrumbs'][] = $searchModel->user->email;
?>
<div class="order-index">

    <!-- <h2>Информация о покупателе</h2> -->
    <?php Pjax::begin(['linkSelector'=>'th a']); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= DetailView::widget([
        'model' => $searchModel->user,
        'attributes' => [
            'name',
            'phone',
        ],
    ]) ?>
    <h2><?= Html::encode($this->title) ?></h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'created_at',
            [
                'label'=>'Дата создания',
                'value'=>function($data)
                {
                    return $data['created_at'];
                },
                'visible'=>!$searchModel->is_complete,
            ],
            [
                'label'=>'Дата завершения',
                'value'=>function($data)
                {
                    return $data['complete_time'];
                },
                'visible'=>$searchModel->is_complete,
            ],

            //'user_name',
            //'email:email',
            //'phone',
            //'user_id',
            'price_sum',

            /*
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ],
             * 
             */
                    'comment',
            /*
            [
                'label'=>'Статус заказа',
                'value'=>function($data)
                {
                    return ($data['is_complete']?'Завершен':'Не завершен');
                }
            ],
             * 
             */

            [
                'label'=>'Просмотр',
                'value'=>function($data)
                {
                    $url = Url::to(['order-item/index','order_id'=>$data['id']]);
                    return Html::a('Посмотреть товары', $url);
                    /*return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                'title' => 'Просмотр',
                                    ]);*/
                    
                },
                'format'=>'raw',
            ]
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
