<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'created_at',
            
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
                    $url = Url::to(['index','user_id'=>$data['user_id']]);
                    return Html::a('Посмотреть заказы', $url);
                    /*return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                                'title' => 'Просмотр',
                                    ]);*/
                    
                },
                'format'=>'raw',
            ]
        ],
    ]); ?>
</div>
