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

    <?php /* echo $form->field($model, 'original_id')->widget(Select2::classname(), [
            'value' => $model->original_id,
            'initValueText' => $model->originalNumber,
            'options' => ['placeholder' => 'Введите оригинальный номер'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => Url::to(['original-number-list']),
                    'dataType' => 'json',
                ],
            ]
        ]) 
     * 
     */
    ?>
    
    <?= $form->field($model, 'originalNumber')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'producer_name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'count')->textInput() ?>
    
    <?php /*echo $form->field($model, 'producer_name')->widget(Select2::classname(), [
            'initValueText' => $model->producer_name,
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
     * 
     */
    ?>
    
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    
    <?php //echo $form->field($model, 'price_change_time')->textInput() ?>
    
    <?= $form->field($model, 'is_visible')->checkbox() ?>
    
    <div class="form-group">
        <?= Html::a('Назад', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
