<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Alert;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use common\models\Config;

$this->title = 'Товары';
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php if(Yii::$app->session->hasFlash('delete_product_error')): ?>
        <?=Alert::widget([
            'options'=>[
                'class'=>'alert-danger'
            ],
            'body'=>'<span class="alert-message">'.Yii::$app->session->getFlash('delete_product_error').'</span>'
        ])?>
    <?php endif; ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'originalNumber',
            'number',
            'name',
            'producer_name',
            'count',
            'price',
            'price_change_time',
            [
                'attribute'=>'is_visible',
                'value'=>function($data){
                    return $data->visibleName;
                },
                'filter'=>$searchModel->visibleList
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    
    <?=Html::beginForm(['load-xml'], 'post', ['enctype'=>'multipart/form-data'])?>
        Загрузить XML файл для добавления новых товаров <?=Html::fileInput('filename')?>
        <?=Html::submitButton('Загрузить') ?>
    <?=Html::endForm();?>
</div>
