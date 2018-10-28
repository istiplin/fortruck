<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список покупателей';
$this->params['breadcrumbs'][] = $searchModel->statusName;
?>
<div class="order-index">

    <!-- <h1><?php// echo Html::encode($this->title) ?></h1> -->

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <h1><?=$this->title?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            //'created_at',
            [
                'label'=>'Дата создания',
                'value'=>function($data)
                {
                    return $data['created_at'];
                },
                'visible'=>!$searchModel->is_complete,
            ],
                        
            //'complete_time',
            [
                'label'=>'Дата завершения',
                'value'=>function($data)
                {
                    return $data['complete_time'];
                },
                'visible'=>$searchModel->is_complete,
            ],
                        
            //'user_name',
            'email',
            //'phone',
            //'user_id',
            [
                'label'=>'Имя',
                'value'=>function($data){
                    if (strlen($data->user_id))
                        return $data->user->name;
                    else
                        return $data->user_name;
                }
            ],
            [
                'label'=>'Телефон',
                'value'=>function($data){
                    if (strlen($data->user_id))
                        return $data->user->phone;
                    else
                        return $data->phone;
                }
            ],
            'count',
                    /*
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update}',
            ],
                     * 
                     */
            [
                'label'=>'Просмотр',
                'value'=>function($data)
                {
                    //если заказов больше одного
                    if($data['count']>1)
                    {
                        //создаем ссылку на список заказов
                        $url = Url::to(['index','user_id'=>$data['user_id'],'is_complete'=>$data['is_complete']]);
                        return Html::a('Посмотреть заказы', $url);
                    }
                    //иначе если заказ один
                    else
                    {
                        //создаем ссылку на список товаров данного заказа
                        $url = Url::to(['order-item/index','order_id'=>$data['id'],'is_complete'=>$data['is_complete']]);
                        return Html::a('Посмотреть товары', $url);
                    }
                },
                'format'=>'raw',
            ]
        ],
    ]); ?>
</div>
