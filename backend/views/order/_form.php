<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>
    
        <?= DetailView::widget([
                'model' => $model->user,
                'attributes' => [
                    'name',
                    'phone',
                ],
        ])
        ?>

        <?= $form->field($model, 'comment')->textarea() ?>
        <?= $form->field($model, 'is_complete')->checkbox() ?>

        <div class="form-group">
            <?= Html::a('Назад', ['order-item/index', 'order_id'=>$model->id], ['class' => 'btn btn-success']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
