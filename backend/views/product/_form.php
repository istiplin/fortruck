<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Analog;
use common\models\Producer;

use yii\web\JsExpression;
use yii\helpers\Url;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'analog_id')->widget(Select2::classname(), [
            'initValueText' => $model->analogName,
            'options' => ['placeholder' => 'Введите наименование типа аналога'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => Url::to(['analog-list']),
                    'dataType' => 'json',
                ],
            ]
        ]) 
    ?>
    
    <?= $form->field($model, 'producer_id')->widget(Select2::classname(), [
            'initValueText' => $model->producerName,
            'options' => ['placeholder' => 'Введите наименование производителя'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 1,
                'ajax' => [
                    'url' => Url::to(['producer-list']),
                    'dataType' => 'json',
                ],
            ]
        ]) 
    ?>

    <?= $form->field($model, 'cost_price')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group">
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
