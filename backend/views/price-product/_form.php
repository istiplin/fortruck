<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Analog;
use common\models\Producer;

use yii\web\JsExpression;
use yii\helpers\Url;


use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'originalNumber',
            'number',
            'name',
            'producer_name',
            'price_change_time'
        ],
    ]) ?>
    
    <?php $form = ActiveForm::begin(); ?>
    
        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

        <?php //echo $form->field($model, 'price_change_time')->textInput() ?>

        <div class="form-group">
            <?= Html::a('Назад', ['index'], ['class' => 'btn btn-success']) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
