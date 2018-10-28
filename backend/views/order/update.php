<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = 'Редактирование заказа № ' . $model->id;
$this->params['breadcrumbs']=[];
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['order/users']];
$this->params['breadcrumbs'][] = [
                                    'label' => $model->user->email, 
                                    'url' => ['order/index','user_id'=>$model->user_id]
                                ];
$this->params['breadcrumbs'][] = ['label' => 'Заказ №'.$model->id, 'url' => ['order-item/index', 'order_id'=>$model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
