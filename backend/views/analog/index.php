<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\AnalogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список типов аналогов';
$this->params['breadcrumbs'][] = ['label'=>'Меню для работы с товарами','url'=>['site/product-editor-menu']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="analog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить аналог', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
        if(Yii::$app->session->hasFlash('deleteErrorMessage'))
            echo Yii::$app->session->getFlash('deleteErrorMessage');
    ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            'name',
            'factory_number',

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
