<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\Url;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-form">


    
    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'product_id')->widget(Select2::classname(), [
            'value' => $model->product_id,
            'initValueText' => $model->productNumber,
            'options' => ['placeholder' => 'Введите артикул товара'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => Url::to(['product/number-list']),
                    'dataType' => 'json',
                ],
            ]
        ]);
    ?>

    <?php //echo $form->field($model, 'productNumber')->textInput(); ?>
    
    <?= $form->field($model, 'count')->textInput() ?>
    
    <?= $form->field($model, 'comment')->textarea() ?>

    <div class="form-group">
        <?= Html::a('Назад', ['index','order_id'=>$model->order_id], ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
