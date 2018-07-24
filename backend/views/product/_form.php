<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Analog;
use common\models\Producer;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'analog_id')->dropDownList(Analog::find()->select('name,id')->indexBy('id')->asArray()->column()) ?>
    
    <?= $form->field($model, 'producer_id')->dropDownList(Producer::find()->select('name,id')->indexBy('id')->asArray()->column()) ?>

    <div class="form-group">
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
