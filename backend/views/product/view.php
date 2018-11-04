<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

?>
<div class="product-view">
    <h1>Просмотр</h1>
    <p>
        <?= Html::a('ОК', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Редактиовать еще раз', ['update','id'=>$model->id], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
                }
            ]
        ],
    ]) ?>

</div>
