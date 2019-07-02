<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?=Html::hiddenInput('normNumber')?>
    
    <?=Html::hiddenInput('brandName')?>
    
    <?= $form->field($model, 'price')->textInput() ?>
    
    <?= $form->field($model, 'count')->textInput() ?>
    
    <?= $form->field($model, 'comment')->textarea() ?>

    <div class="form-group">
        <?= Html::a('Назад', ['index','order_id'=>$model->order_id], ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
