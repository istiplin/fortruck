<?php

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use common\models\Role;
    /* @var $this yii\web\View */
    /* @var $model common\models\User */
    /* @var $form yii\widgets\ActiveForm */
    
    use yii\web\View;
    //use frontend\assets\AjaxFormAsset;
    use common\assets\AjaxFormAsset;

    AjaxFormAsset::register($this);
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'action'=>['update', 'id' => $model->id],
        //'enableClientValidation' => false,
    ]); 
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true,'disabled'=>false]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::a('Назад', ['index'],['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>