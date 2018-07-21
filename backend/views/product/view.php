<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

?>
<div class="product-view">
    <h1>Просмотр</h1>
    <p>
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'number',
            'name',
            'analog_id',
            'producer_id',
        ],
    ]) ?>

</div>
